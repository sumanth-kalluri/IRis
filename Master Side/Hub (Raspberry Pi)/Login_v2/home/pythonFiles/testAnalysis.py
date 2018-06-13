'''
 * Project          : IRis
 * Purpose          : Jharkhand Government Project, Ranchi
 * Module           : Master Hub (RasPi) [Main Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : WhizMantra Educational Services
 * 
'''

import os
import sys
from PyQt4.QtCore import *
from PyQt4.QtGui import *
import csvOps

#Minimize Window
def minimize():
    resWind.showMinimized()

#Reset Form
def reset():
    #Reset The Displayed Stats
    resetStats()
    
    #Reset The Class Box
    classSelect.dropdown.blockSignals(True)
    classSelect.dropdown.setCurrentIndex(0)
    
    #Reset The Subject Box & Subject Directory
    global subjDir
    subjDir=""
    subjSelect.dropdown.blockSignals(True)
    subjSelect.dropdown.clear()
    subjSelect.dropdown.addItems(["Select Subject"])

    #Reset The Topic Box & Topic Directory
    global topicDir
    topicDir=""
    topicSelect.dropdown.blockSignals(True)
    topicSelect.dropdown.clear()
    topicSelect.dropdown.addItems(["Select Topic"])

    #Reset The Test Box & Test Directory
    global testDir
    testDir=""
    testSelect.dropdown.blockSignals(True)
    testSelect.dropdown.clear()
    testSelect.dropdown.addItems(["Select Test"])

    #Reset The Student Box
    studSelect.dropdown.blockSignals(True)
    studSelect.dropdown.clear()
    studSelect.dropdown.addItems(["Select Student ID"])

    classSelect.dropdown.blockSignals(False)
    

#Close Window
def close():
    sys.exit()

def classChange(newClass) :
    #Reset The Displayed Stats
    resetStats()

    #Reset The Topic Box & Topic Directory
    global topicDir
    topicDir=""
    topicSelect.dropdown.blockSignals(True)
    topicSelect.dropdown.clear()
    topicSelect.dropdown.addItems(["Select Topic"])

    #Reset The Test Box & Test Directory
    global testDir
    testDir=""
    testSelect.dropdown.blockSignals(True)
    testSelect.dropdown.clear()
    testSelect.dropdown.addItems(["Select Test"])

    #Reset The Student Box
    studSelect.dropdown.blockSignals(True)
    studSelect.dropdown.clear()
    studSelect.dropdown.addItems(["Select Student ID"])

    if newClass>0 :
        global subjDir
        subjDir = classDir + str(classSelect.dropdown.currentText()) + '/'
        subjSelect.dropdown.addItems(os.listdir(subjDir))
        subjSelect.dropdown.blockSignals(False)


    
def subjChange(newSubj) :
    #Reset The Displayed Stats
    resetStats()
    
    #Reset The Topic Box
    topicSelect.dropdown.blockSignals(True)
    topicSelect.dropdown.clear()
    topicSelect.dropdown.addItems(["Select Topic"])

    #Reset The Test Box & Test Directory
    global testDir
    testDir=""
    testSelect.dropdown.blockSignals(True)
    testSelect.dropdown.clear()
    testSelect.dropdown.addItems(["Select Test"])

    #Reset The Student Box
    studSelect.dropdown.blockSignals(True)
    studSelect.dropdown.clear()
    studSelect.dropdown.addItems(["Select Student ID"])

    if newSubj>0 :
        global topicDir
        topicDir = subjDir + str(subjSelect.dropdown.currentText()) + '/'
        topicSelect.dropdown.addItems([topicName for topicName in os.listdir(topicDir) if os.path.isdir(topicDir + topicName)])
        topicSelect.dropdown.blockSignals(False)



def topicChange(newTopic) :
    #Reset The Displayed Stats
    resetStats()
    
    #Reset The Test Box
    testSelect.dropdown.blockSignals(True)
    testSelect.dropdown.clear()
    testSelect.dropdown.addItems(["Select Test"])

    #Reset The Student Box & Student Directory
    studSelect.dropdown.blockSignals(True)
    studSelect.dropdown.clear()
    studSelect.dropdown.addItems(["Select Student ID"])

    if newTopic>0 :
        global testDir
        testDir = topicDir + str(topicSelect.dropdown.currentText()) + '/'
        testSelect.dropdown.addItems([testName[:len(testName)-4] for testName in os.listdir(testDir) if testName[0:3]!="map"])                
        testSelect.dropdown.blockSignals(False)



def testChange(newTest) :
    #Reset The Displayed Stats
    resetStats()

    #Reset The Student Box & Student Directory
    studSelect.dropdown.blockSignals(True)
    studSelect.dropdown.clear()
    studSelect.dropdown.addItems(["Select Student ID"])
    
    if newTest>0 :
        #Read The Response File For The Selected Test
        global responses
        responses = csvOps.csvRead(testDir + str(testSelect.dropdown.currentText()) + ".csv")

        #Retain Error Codes And Remove Roll Number And Options Entered For Each Student (Row)
        responses = [[student[2*i] for i in range(0,len(student)/2 + 1)] for student in responses]

        #Add Student IDs To DropDown
        studSelect.dropdown.addItems([student[0] for student in responses[1:]])

        errorMapFile = testDir + "map_" + str(testSelect.dropdown.currentText()) + ".csv"
        global errorMap
        # If Error Map Exists, Read The File Which Maps Error Codes With Their Description
        if os.path.isfile(errorMapFile) :
            errorMap = csvOps.csvRead(testDir + "map_" + str(testSelect.dropdown.currentText()) + ".csv")

            #Filter Out Error Codes And Description
            errorMap = [[errorRow[3],errorRow[5]] for errorRow in errorMap]

        #If File Does Not Exist, Store An Empty List
        else :
            errorMap = []
            
        studSelect.dropdown.blockSignals(False)

    

def studChange(newStud) :
    if newStud > 0 :
        #Select The Row Corresponding To The Student ID
        student = responses[newStud][1:]
        
        total = len(student)
        attemptedBar.setMaximum(total)
        correctBar.setMaximum(total)

        #Remove All No Response Questions
        i=0
        while i<len(student):
            if student[i] == 'x' :
                del student[i]
            else :
                i=i+1

        attempted = len(student)
        
        correct = 0
        
        i=0
        while i<len(student):
            if student[i] == '0' :
                del student[i]
                correct = correct + 1
            else :
                i=i+1

        attemptedNum.lab.setText(str(attempted) + "/" + str(total) + "\t")
        correctNum.lab.setText(str(correct) + "/" + str(total) + "\t")

        attemptedBar.setMaximum(total)
        attemptedBar.setValue(attempted)

        correctBar.setMaximum(total)
        correctBar.setValue(correct)

        errorsList = []

        i=0
        while i<len(student) :
            errCode = student[0]
            errNum = 1
            del student[0]
            j=0
            while j<len(student) :
                if errCode == student[j] :
                    errNum = errNum + 1
                    del student[j]
                else :
                    j = j+1
            errorsList.append([errCode,errNum])

        #If Error Map If Defined, Replace Codes With Description
        if len(errorMap)>0:    
            for error in errorsList :
                for desc in errorMap[1:] :
                    if error[0]==desc[0] :
                        error[0] = desc[1]
                        break

        wrong = attempted - correct
         
        clearLay(errorBars.layout())

        for i in range(0,len(errorsList)) :
            errorLab = NLabel(str(errorsList[i][0]), 21)
            errorBars.layout().addWidget(errorLab.lab,i,0)

            errorNum = NLabel("\t" + str(errorsList[i][1]),26)
            errorBars.layout().addWidget(errorNum.lab,i,1)

            errorBar = QProgressBar()
            errorBar.setStyleSheet("QProgressBar {background-color: #000000; border: 0px} QProgressBar::chunk {background-color: #3386FF;}")
            errorBar.setFixedHeight(40)
            errorBar.setTextVisible(False)
            errorBar.setMaximum(wrong)
            errorBar.setValue(int(errorsList[i][1]))
            errorBars.layout().addWidget(errorBar,i,2)

#Function To Clear The Scroll Area
def clearLay(lay) :
    prevCount = lay.count()
    for i in range (0,prevCount) :
        child = lay.takeAt(0)
        if child.widget() is not None :
            child.widget().deleteLater()
        elif child.layout() is not None :
            clearLay(child.layout())

#Function To Reset The Stats
def resetStats():
    #Clear The Scroll Area
    clearLay(errorBars.layout())

    #Clear The Scores
    attemptedNum.lab.setText("0/0\t")
    correctNum.lab.setText("0/0\t")

    #Reset The Score Bars
    attemptedBar.setValue(0)
    correctBar.setValue(0)

#Class For Label
class NLabel() :
    def __init__(self, labText, fontSize) :
        self.lab = QLabel()
        pal = self.lab.palette()
        pal.setColor(resWind.foregroundRole(), QColor(255,255,255,255))
        self.lab.setPalette(pal)
        font = self.lab.font()
        font.setPointSize(fontSize)
        self.lab.setFont(font)
        self.lab.setText(labText)
        

#Class For Command Button
class NCommandButton() :
    def __init__(self, buttonText, clickFunc, backColor) :
        self.button = QPushButton()
        self.button.setStyleSheet("QPushButton {background-color: " + backColor + " ; color: #000000; border: 5px solid black; border-radius: 15px;}")
        font = self.button.font()
        font.setPointSize(32)
        self.button.setFont(font)
        self.button.clicked.connect(clickFunc)
        self.button.setText(buttonText)
        

#Class For DropDown Menu
class NDropDown() :
    def __init__(self, entries, changeFunc) :
        self.dropdown = QComboBox()
        #self.dropdown.setStyleSheet("QComboBox {background-color: linear-gradient(red, yellow);}")
        self.dropdown.addItems(entries)
        font = self.dropdown.font()
        font.setPointSize(32)
        self.dropdown.setFont(font)
        self.dropdown.setEditable(True)
        self.dropdown.lineEdit().setReadOnly(True)
        self.dropdown.lineEdit().setAlignment(Qt.AlignCenter)
        self.dropdown.currentIndexChanged.connect(changeFunc)        

if __name__ == '__main__':
    classDir = "../eva-report/reports/"

    subjDir=""
    topicDir=""
    testDir=""

    responses=[]
    errorMap=[]
    

    app = QApplication(sys.argv)
    resWind = QWidget()
    pal = resWind.palette()
    pal.setColor(resWind.backgroundRole(), QColor(20, 20, 20, 255))
    resWind.setPalette(pal)
  
    #Drop Down Menu For Class
    classSelect = NDropDown(["Select Class"] + os.listdir(classDir), classChange)

    #Drop Down Menu For Subject
    subjSelect = NDropDown(["Select Subject"], subjChange)

    #Drop Down Menu For Topic
    topicSelect = NDropDown(["Select Topic"], topicChange)

    #Drop Down Menu For Tests Conducted
    testSelect = NDropDown(["Select Test"], testChange)

    #Drop Down Menu For Student Roll Numbers
    studSelect = NDropDown(["Select Student ID"], studChange)

    attemptedLab = NLabel("Attempted\t", 24)
    
    correctLab = NLabel("Correct\t", 24)

    attemptedNum = NLabel("0/0\t", 24)

    correctNum = NLabel("0/0\t", 24)

    attemptedBar = QProgressBar()
    attemptedBar.setStyleSheet("QProgressBar {background-color: #FFFFFF; border: 0px} QProgressBar::chunk {background-color:#FF5733}")
    attemptedBar.setFixedHeight(40)
    attemptedBar.setTextVisible(False)
    attemptedBar.setValue(0)
    correctBar = QProgressBar()
    correctBar.setStyleSheet("QProgressBar {background-color: #FFFFFF; border: 0px} QProgressBar::chunk {background-color:#FF5733}")
    correctBar.setFixedHeight(40)
    correctBar.setTextVisible(False)
    correctBar.setValue(0)

    errorHeadLab = NLabel("Analysis Of Errors", 24)

    #Buttons For Minimize, Reset And Close
    minimBut = NCommandButton("Minimize", minimize, "#FFFFFF")
    resetBut = NCommandButton("Reset", reset, "#00FF00")
    closeBut = NCommandButton("Close", close, "#FF0000")

    #Form Layout   
    optRow1 = QHBoxLayout()
    optRow2 = QHBoxLayout()
    
    optRow1.addWidget(classSelect.dropdown)
    optRow1.addWidget(subjSelect.dropdown)
    
    optRow2.addWidget(topicSelect.dropdown)
    optRow2.addWidget(testSelect.dropdown)
    optRow2.addWidget(studSelect.dropdown)
    
    #Score Layout
    scoreBox = QHBoxLayout()
    
    scoreLabs = QVBoxLayout()
    scoreLabs.addWidget(attemptedLab.lab)
    scoreLabs.addWidget(correctLab.lab)

    scoreNums = QVBoxLayout()
    scoreNums.addWidget(attemptedNum.lab)
    scoreNums.addWidget(correctNum.lab)

    scoreBars = QVBoxLayout()
    scoreBars.addWidget(attemptedBar)
    scoreBars.addWidget(correctBar)
    
    scoreBox.addLayout(scoreLabs)
    scoreBox.addLayout(scoreNums)
    scoreBox.addLayout(scoreBars)
    
    #Error Bar Graph Layout
    errorBars = QScrollArea()
    errorBars.setWidgetResizable(True)
    errorBars.setStyleSheet("QScrollArea {background-color: #000000}")

    errorBox = QGridLayout()
    errorBars.setLayout(errorBox)

    #Buttons Layout
    buttons = QHBoxLayout()
    buttons.addWidget(minimBut.button)
    buttons.addWidget(resetBut.button)
    buttons.addWidget(closeBut.button)
    

    resBox = QVBoxLayout()
    resBox.setContentsMargins(30,30,30,30)

    resBox.addLayout(optRow1)
    resBox.addLayout(optRow2)
    resBox.addWidget(QLabel()) #Dummy Space

    resBox.addLayout(scoreBox)
    resBox.addWidget(QLabel()) #Dummy Space

    resBox.addWidget(errorHeadLab.lab)
    resBox.addWidget(errorBars)

    resBox.addLayout(buttons)
    
    resWind.setLayout(resBox)
    
    resWind.showFullScreen()

    resWind.setWindowTitle("Result Generator")
    resWind.setWindowFlags(Qt.WindowStaysOnTopHint)
    resWind.show()

      
    sys.exit(app.exec_())

    
    
