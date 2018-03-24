'''
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master Hub (RasPi) [Main Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
'''

import csvOps
import irOps
from time import sleep
from copy import deepcopy as dc
import serial
import sys
import os

#Initiate Serial Device
uno = serial.Serial("/dev/ttyUSB0", 115200)

#Clear The Console Screen
os.system('clear')

qFile = raw_input("Please Enter The Name Of The Question Set :\n")

aFile = raw_input("\nPlease Enter The Name Of The Response Set :\n")

#Load The Questions From The CSV File
print ("\nLoading Questions . . .")
questions = csvOps.csvRead("./Question Sets/" + qFile + ".csv")
print ("Questions Loaded")
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
print ("\nInitializing Remotes . . .")
slavesActive = dc(irOps.init(0,uno))
print ("Remotes Initialized")

print ("\nPress Enter To Start The Quiz")
#Accept Enter Key
dummy = raw_input()

#Loop Over Questions Extracted From The Question Set
for question in questions[1:] :
    #Clear The Console Screen
    os.system('clear')
    sleep(1)

    #Display Questions
    print (question[0] + ". " + question[1] + " ?")
    sleep(1)

    #Display Options
    print ("A) " + question[2])
    print ("B) " + question[3])
    print ("C) " + question[4])
    print ("D) " + question[5])

    #Reset The Inputs Of All Slaves
    irOps.reset(0,uno)

    #Display Time Left In Seconds
    print ("\nTime Remaining : ")
    for sec in range(int(question[6]),0,-1):
        #Display 2 Digit Numbers
        sys.stdout.write('\r' + str(sec).rjust(2, '0'))
        sys.stdout.flush()
        sleep(1)
    sys.stdout.write('\r00')
    sys.stdout.flush()

    #Send Stop Input Signal
    irOps.stop(0,uno)
    sleep(0.5)

    #Request For User Inputs From Slaves
    irOps.enquire(0,int(question[0]),uno,responses)
    

#Create File And Record Responses
#Response Format : Roll Number, Q1 Response, Q2 Response - - - -, Qn Response
csvOps.csvWrite('./Responses/' + aFile + '.csv', responses)

print "Quiz Complete. Responses Recorded"
