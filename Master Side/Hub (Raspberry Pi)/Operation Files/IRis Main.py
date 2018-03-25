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
import time

def startQuiz() :
    #Get Name Of Question Set
    qFile = quesIP.get()

    #Assign The Same Name To Responses File
    aFile = qFile
    
    #Destroy Existing frames
    timer_Frame.destroy()
    intro_Frame.destroy()

    #Create New Labels For Questions And Responses
    ques_Label = Label(qa_Frame, text="", font=myFont, bg="pink")
    ques_Label.place(x=683,y=50, anchor="center")
    
    optA_Label = Label(qa_Frame, text="", font=myFont, bg="pink")
    optA_Label.place(x=100,y=300)
    
    optB_Label = Label(qa_Frame, text="", font=myFont, bg="pink")
    optB_Label.plaBe(x=783,y=300)

    optC_Label = Label(qa_Frame, text="", font=myFont, bg="pink")
    optC_Label.place(x=100,y=500)

    optD_Label = Label(qa_Frame, text="", font=myFont, bg="pink")
    optD_Label.place(x=783,y=500)
    
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

    qa_Frame.pack()

    #Loop Over Questions Extracted From The Question Set
    for question in questions[1:] :
        #Display Questions
        ques_Label.config(text = question[0] + ". " + question[1] + " ?")

        #Reset The Inputs Of All Slaves
        irOps.reset(0,uno)

        #Display Options
        optA_Label.config(text = "A) " + question[2])
        optA_Label.config(text = "B) " + question[3])
        optA_Label.config(text = "C) " + question[4])
        optA_Label.config(text = "D) " + question[5])

        qa_Frame.pack()
              
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
        ques_Label.config(text = "")
        optA_Label.config(text = "")
        optA_Label.config(text = "")
        optA_Label.config(text = "")
        optA_Label.config(text = "")

        qa_Frame.pack()

        #Request For User Inputs From Slaves
        irOps.enquire(0,int(question[0]),uno,responses)
        
        

    #Create File And Record Responses
    #Response Format : Roll Number, Q1 Response, Q2 Response - - - -, Qn Response
    csvOps.csvWrite('./Responses/' + aFile + '.csv', responses)

    ques_Label.config(text = "Quiz Complete. Responses Recorded")
    qa_Frame.pack()

    GUI.destroy()
    
    
#Initiate Serial Device
uno = serial.Serial("/dev/ttyUSB0", 115200)

#Clear The Console Screen
os.system('clear')

#Initiate GUI
GUI = Tk()

#Get Screen Dimensions
scrHgt = GUI.winfo_screenheight()
scrWdt = GUI.winfo_screenwidth()

#Configure The GUI
GUI.overrideredirect(True)
GUI.geometry("{0}x{1}+0+0".format(scrWdt, scrHgt))
GUI.focus_set()

#Specify Fonts
title_Font = tkFont.Font(family = 'Helvetica', size = 36, weight = 'bold')
timer_Font = tkFont.Font(family = 'Helvetica', size = 40, weight = 'bold')
other_Font = tkFont.Font(family = 'Helvetica', size = 25, weight = 'bold')

#Create Start Button
startBut_Frame = Frame(GUI,width=1920, height=1080, bg="yellow")
startBut_Frame.pack()
startButton = Button(startBut_Frame, text = "START TEST", font=other_Font, command=startQuiz, bg="yellow").pack()
startBut_Frame.pack(side = BOTTOM)

#Create Start Frame
intro_Frame = Frame(GUI,width=1920, height=1080, bg="blue")
intro.pack()

#Create Timer
timer_Frame = Frame(GUI,width=1920, height=1080, bg="pink")
timer_Frame.pack(side = BOTTOM)
qTimer =StringVar()
timer_Label = Label(timer_Frame, textvariable=qTimer, font=timer_Font, bg='white', fg='blue', relief='raised', bd=3).pack()

#Create Topic Label
topic_Label = Label(intro_Frame, text="IRis QUIZ", font=myFont, bg="blue")
topic_Label.place(x=500,y=10)

#Create Label For Content On First Page
content_Label = Label(intro_Frame, text="Question Set", font=myFont1, bg="blue")
content_Label.place(x=350,y=200)
intro_Frame.pack()

#Create Input Box For Question Set
qSetName = StringVar()
quesIp = Entry(intro_Frame,textvariable=qSetName, font=myFont1)
quesIp.place(x=700,y=205)
intro_Frame.pack()

qa_Frame = Frame(GUI,width=1920, height=1080, bg="pink")
qa_Frame.pack()

GUI.mainloop()
