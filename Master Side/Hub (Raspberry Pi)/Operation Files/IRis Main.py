'''
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master Hub (RasPi) [Main Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik & Sai Ruthvik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
'''

import csvOps
import irOps
import serial
import sys
import os
from time import sleep
from copy import deepcopy as dc
from Tkinter import *
import tkFont

#Initiate GUI
GUI = Tk()

#Get Screen Dimensions
scrHgt = GUI.winfo_screenheight()
scrWdt = GUI.winfo_screenwidth()

#Configure The GUI
GUI.configure(bg="SkyBlue2")
GUI.overrideredirect(True)
GUI.geometry("{0}x{1}+0+0".format(scrWdt, scrHgt))
GUI.focus_set()

#Specify Fonts
title_font = tkFont.Font(family = 'Helvetica', size = 50, weight = 'bold')
timer_font = tkFont.Font(family = 'Helvetica', size = 40, weight = 'bold')
other_font = tkFont.Font(family = 'Helvetica', size = 30, weight = 'bold')

startBut_frame = Frame(GUI,width=1920, height=1080, bg="magenta")
startBut_frame.pack()

#Create Intro Frame
intro_frame = Frame(GUI,width=1920, height=1080, bg="SkyBlue2")
intro_frame.pack()


#Create Timer
timer_frame = Frame(GUI,width=1920, height=1080, bg="SkyBlue2")
timer_frame.pack(side=BOTTOM)
qTimer = StringVar()
Label(timer_frame, textvariable=qTimer, font=timer_font, bg='white', fg='blue', relief='raised', bd=3).pack()

#Create Topic Label
topic_label = Label(intro_frame, text="IRis QUIZ", font=title_font, bg="SkyBlue2")
topic_label.place(x=960,y=50,anchor="center")

#Create Label For Content On First Page
content_label = Label(intro_frame, text="TOPIC", font=other_font, bg="SkyBlue2")
content_label.place(x=500,y=400)
intro_frame.pack()

qa_frame = Frame(GUI,width=1920, height=1080, bg="SkyBlue2")
qa_frame.pack()

'''#Create Input Box For Question Set
select_label = Label(intro_frame, text="INDIA", font=other_font, bg="SkyBlue2")
select_label.place(x=1000,y=400)
intro_frame.pack()'''

list1=Listbox(intro_frame,height=3,width=15,font=other_font)
list1.insert(1,"INDIA")
list1.insert(2,"ENGLISH")
list1.insert(3,"MATH")
list1.place(x=960,y=405)
intro_frame.pack()

def startQuiz() :
    selected = list1.curselection()
    if selected == (0,) :
        qFile = "India"
    elif selected == (1,) :
        qFile = "English"
    elif selected == (2,) :
        qFile = "Math"
    
    #Assign The Same Name To Responses File
    aFile = qFile
    
    #Destroy Existing frames
    startBut_frame.destroy()
    intro_frame.destroy()

    #Create New Labels For Questions And Responses
    ques_label = Label(qa_frame, text="", font=other_font, bg="SkyBlue2")
    ques_label.place(x=960,y=100, anchor="center")
    
    optA_label = Label(qa_frame, text="", font=other_font, bg="SkyBlue2")
    optA_label.place(x=300,y=400)
    
    optB_label = Label(qa_frame, text="", font=other_font, bg="SkyBlue2")
    optB_label.place(x=1260,y=400)

    optC_label = Label(qa_frame, text="", font=other_font, bg="SkyBlue2")
    optC_label.place(x=300,y=600)

    optD_label = Label(qa_frame, text="", font=other_font, bg="SkyBlue2")
    optD_label.place(x=1260,y=600)
    
    #Load The Questions From The CSV File
    questions = csvOps.csvRead("./Question Sets/" + qFile + ".csv")
    #CSV File Format : Q. No., Question, Option A, Option B, Option C, Option D, Time For Question

    #Generate Default Blank Responses List
    responses = [["-" for col in range(0,len(questions))] for row in range(0,64)]

    #Create The Header Of The Responses List
    responses[0][0] = "Roll Number"
    for qNo in range(1,len(questions)) :
        responses[0][qNo] = "Q" + str(qNo)

    #Add Roll Numbers To The Responses List
    for roll in range(1,64) :
        responses[roll][0] = str(roll)

    #Initialilze Remotes To Find Active Ones
    slavesActive = dc(irOps.init(0,uno))

    qa_frame.pack()

    #Loop Over Questions Extracted From The Question Set
    for question in questions[1:] :
        #Display Questions
        ques_label.config(text = question[0] + ". " + question[1] + " ?")

        #Reset The Inputs Of All Slaves
        irOps.reset(0,uno)

        #Display Options
        optA_label.config(text = "A) " + question[2])
        optB_label.config(text = "B) " + question[3])
        optC_label.config(text = "C) " + question[4])
        optD_label.config(text = "D) " + question[5])

        qa_frame.pack()
     
        for sec in range(int(question[6]), 0, -1):
            # Format As 2 Digit Integers, Fills With Zero To The Left
            timerFormat = "{:02d}:{:02d}".format(*divmod(sec, 60))
            
            qTimer.set(timerFormat)
            GUI.update()
            sleep(1)

        # Format As 2 Digit Integers, Fills With Zero To The Left
        timerFormat = "{:02d}:{:02d}".format(*divmod(0, 60))
        
        qTimer.set(timerFormat)
        GUI.update()
        
        #Send Stop Input Signal
        irOps.stop(0,uno)
        sleep(0.5)

        #Clear Questions And Options        
        ques_label.config(text = "")
        optA_label.config(text = "")
        optB_label.config(text = "")
        optC_label.config(text = "")
        optD_label.config(text = "")

        qa_frame.pack()

        #Request For User Inputs From Slaves
        irOps.enquire(0,int(question[0]),uno,responses)
        
        

    #Create File And Record Responses
    #Response Format : Roll Number, Q1 Response, Q2 Response - - - -, Qn Response
    csvOps.csvWrite('./Responses/' + aFile + '.csv', responses)

    ques_label.config(text = "Quiz Complete. Responses Recorded")
    qa_frame.pack()

    GUI.destroy()
    
    
#Initiate Serial Device
uno = serial.Serial("/dev/ttyUSB0", 115200)

#Create Start Button
startButton = Button(startBut_frame, text = "START TEST", font=other_font, command=startQuiz, bg="light yellow").pack()
startBut_frame.pack(side = BOTTOM)

print ("Before Main Loop")

GUI.mainloop()
