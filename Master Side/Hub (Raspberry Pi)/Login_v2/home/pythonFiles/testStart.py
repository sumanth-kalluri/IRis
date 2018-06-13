'''
 * Project          : IRis
 * Purpose          : Jharkhand Government Project, Ranchi
 * Module           : Master Hub (RasPi) [Main Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : WhizMantra Educational Services Pvt. Ltd.
 * 
'''

import csvOps
import irOps
import serial
import os
import sys
from time import sleep
from copy import deepcopy as dc
from PyQt4.QtCore import *
from PyQt4.QtGui import *

quesTime = 10
quesNum = 0
slavesActive = []
initRemotes = False

class NLabel() :
    def __init__(self,fontsize,align, bold):
        self.label = QLabel()
        font = self.label.font()
        font.setPointSize(fontsize)
        font.setBold(bold)
        font.setFamily("OpenSymbol")
        self.label.setFont(font)
        self.label.setAlignment(align)
        self.label.setWordWrap(True)
        pal = self.label.palette()
        pal.setColor(testWind.foregroundRole(), QColor(255,255,255,255))
        self.label.setPalette(pal)

def dispNextQues() :    
    #Increment The Question Number By 1
    global quesNum
    quesNum = quesNum + 1

    #If Questions Are Complete, Exit
    if quesNum == len(questions):
        #Create File And Record Responses
        #Response Format : Roll Number, Q1 Response, Q1 Response Error Code, Q2 Response, Q2 Response Error Code - - - -, Qn Response, Qn Response Error Code
        csvOps.csvWrite(respFile, responses)

        #Create An Error Wise Mapping For Each Student
        for roll in range(1,64) :
            errMap = [str(roll)]
            #Check For Each Error Code In Each Response Of A Particular Student
            for error in errorCodes[0][1:] :
                errNum = 0
                for qNo in range(0,len(questions)) :
                    if responses[roll][2*qNo] == error :
                        errNum = errNum + 1
                errMap.append(str(errNum))            
            errorCodes.append(dc(errMap))

        #Since Purpose Of "x" is Complete, Change Name To "Not Attempted"
        errorCodes[0][len(errorCodes[0])-1] = "Not Attempted"

        #Create File And Record Responses
        #Error Distribution Format : Roll Number, Error 1 Repitition, Error 2 Repitition - - - -, Error n Repitition, Not Attempted Repitition
        csvOps.csvWrite(errorFile, errorCodes) 
            
        exit()
    
    question = questions[quesNum]
    global quesTime
    quesTime = int(question[10])

    #Display Question Number
    numLab.label.setText(question[0])
    
    #Display Questions
    quesLab.label.setText(question[1])

    #Reset The Inputs Of All Slaves
    irOps.reset(0,uno)
    
    #Display Options
    optALab.label.setText("A) " + question[2]) 
    optBLab.label.setText("B) " + question[4])
    optCLab.label.setText("C) " + question[6])
    optDLab.label.setText("D) " + question[8])

    #Start Timer
    timeLab.label.setText("00:" + question[10].zfill(2))
    quesTimer.start()
    
def startInit() :
    initTimer.stop()
    global initRemotes
    if not initRemotes :
        #Initialilze Remotes To Find Active Ones
        global slavesActive
        slavesActive = dc(irOps.init(0,uno))

        quesLab.label.setText("All The Best")
        
        initRemotes = True
        
        initTimer.start()

    else :
        global quesTime
        quesTime = 1
        global quesNum
        quesNum = 0
        quesTimer.start()
        

def quesTimeShow() :
    global quesTime
    quesTime = quesTime - 1
    timeLab.label.setText("00:" + str(quesTime).zfill(2))
    if quesTime == 0:
        #Stop The Timer
        quesTimer.stop()

        if quesNum > 0 :
            #Send Stop Input Signal
            irOps.stop(0,uno)
            sleep(0.5)

            #Request For User Inputs From Slaves
            irOps.enquire(0,quesNum,uno,responses, questions)
        
        dispNextQues()


if __name__ == '__main__':
    uno = serial.Serial("/dev/ttyUSB0", 115200)
    sleep(1)
    
    testDir = '/var/www/html/Login_v2/home/currentTest/'
    testList = os.listdir(testDir)

    #Remove .csv Extension From File
    fileName = testList[0][:len(testList[0])-4]

    #Load The Questions With Responses And Error Codes From The File
    questions = csvOps.csvRead(testDir + fileName + ".csv")
    #CSV File Format : Q. No., Question, Option A, Error Code A, Option B, Error Code B, Option C, Error Code C, Option D, Error Code D, Time For Question

    #Empty The currentTest Directory
    os.system("sudo rm -rf " + testDir + "*")
    
    #Create A List For Responses And Error Codes
    responses = [["x" for col in range(0,2*len(questions)-1)] for row in range(0,64)]

    #Create A List For Error-Wise Mapping For each Student
    errorCodes = []

    #Create The Header Of The Responses List
    #Also Pick Out Error Codes From The Test File
    responses[0][0] = "Roll Number"
    for qNo in range(1,len(questions)) :
        responses[0][2*qNo-1] = "Ans" + str(qNo)
        responses[0][2*qNo] = "Ans" + str(qNo) + " Error Code"
        #Record Error Code For Each Option
        for option in range(3,11,2) :
            errorCodes.append(questions[qNo][option])

    #Find Unique Entries
    errorCodes = [list(set(errorCodes))]

    #Remove Correct Code
    if "0" in errorCodes[0] :
        errorCodes[0].remove("0")

    #Add Not Attempted Code To Errors
    errorCodes[0].append("x")

    #Insert Roll Number Header In Top Left Cell
    errorCodes[0].insert(0,"Roll No")
    
    #Add Roll Numbers To The Responses List
    for roll in range(1,64) :
        responses[roll][0] = str(roll)

    #Isolate The Class Number For Which The Test Is Being Conducted
    fileName = fileName[fileName.find('_')+1:]
    testClass = fileName[:fileName.find('_')]

    #Isolate The Test Subject
    fileName = fileName[fileName.find('_')+1:]
    testSub = fileName[:fileName.find('_')]

    #Isolate The Test Topic
    fileName = fileName[fileName.find('_')+1:]
    testTopic = fileName[:fileName.find('_')]

    #Isolate The Test Number
    testNum = fileName[fileName.find('_')+1:]

    respFile = "/var/www/html/Login_v2/home/eva-report/reports/" + testClass + "/" + testSub + "/" + testTopic + "/" + testNum + ".csv"
    print respFile

    errorFile = "/var/www/html/Login_v2/home/testReport/" + testClass + "/" + testSub + "/" + testTopic + "/" + testNum + ".csv"
    print errorFile
    

    headFont = 55
    numFont = 55
    quesFont = 50
    optFont = 50
    timeFont = 50    
    
    app = QApplication(sys.argv)
    testWind = QWidget()
    pal = testWind.palette()
    pal.setColor(testWind.backgroundRole(), QColor(20, 20, 20, 255))
    testWind.setPalette(pal)

    testBox = QVBoxLayout()
    testBox.setContentsMargins(50,10,50,10)
    
    nameLab = NLabel(headFont,Qt.AlignCenter,False)
    #Make The First Letter of Topic Name Capital
    nameLab.label.setText(chr(ord(testSub[0])-32) + testSub[1:] + " Test " + testNum)
    #nameLab.label.setText("Test On")

    numLab = NLabel(quesFont, Qt.AlignCenter, False)
    
    quesLab = NLabel(quesFont, Qt.AlignCenter, True)
    quesLab.label.setText("Initializing . . .")

    optBox = QGridLayout()
    optBox.setContentsMargins(50,10,50,10)
    optBox.setSpacing(100)
    optALab = NLabel(optFont, Qt.AlignLeft, False)
    optBLab = NLabel(optFont, Qt.AlignLeft, False)
    optCLab = NLabel(optFont, Qt.AlignLeft, False)
    optDLab = NLabel(optFont, Qt.AlignLeft, False)
    optBox.addWidget(optALab.label,0,0)
    optBox.addWidget(optBLab.label,0,1)
    optBox.addWidget(optCLab.label,2,0)
    optBox.addWidget(optDLab.label,2,1)

    timeLab = NLabel(quesFont, Qt.AlignCenter, True)

    testBox.addWidget(nameLab.label)
    testBox.addStretch()

    testBox.addWidget(numLab.label)
    
    testBox.addWidget(quesLab.label)
    testBox.addStretch()

    testBox.addLayout(optBox)
    testBox.addStretch()

    testBox.addWidget(timeLab.label)
      
    testWind.setLayout(testBox)
    
    testWind.showFullScreen()

    testWind.setWindowTitle("Test Master")
    testWind.show()

    quesTimer = QTimer()
    quesTimer.setInterval(1000)
    quesTimer.timeout.connect(quesTimeShow)
    quesTimer.stop()

    initTimer = QTimer()
    initTimer.setInterval(500)
    initTimer.timeout.connect(startInit)
    initTimer.start()

    sys.exit(app.exec_())
    

    
