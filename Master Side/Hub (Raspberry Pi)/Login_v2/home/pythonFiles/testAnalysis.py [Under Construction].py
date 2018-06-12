import os
import sys
from PyQt4.QtCore import *
from PyQt4.QtGui import *
import csvOps

def entryChange(newIndex) :
    if studSelect.dropdown.currentIndex() == 0 or testSelect.dropdown.currentIndex() == 0 :
        #If Any Option Is Not Selected, Do Not Proceed
        a=0
    else :
        #Read The Response File For The Selected Test
        results = csvOps.csvRead("../Responses/" + respOffline[testSelect.dropdown.currentIndex() - 1] + ".csv")

        #Read The File Which Maps Error Codes With Their Description
        errorDesc = csvOps.csvRead("../Errors/" + respOffline[testSelect.dropdown.currentIndex() - 1] + ".csv")

        #Get The Responses For The Selected Student
        student = results[studSelect.dropdown.currentIndex()]

        #Retain Error Codes And Remove Roll Number And Options Entered
        student = [student[2*i] for i in range(1,len(student)/2 + 1)]
        
        total = len(student)
        attemptedBar.setMaximum(total)
        correctBar.setMaximum(total)

        #Remove All No Response Questions
        i=0
        while i<len(student):
            if student[i] == '-' :
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

        attemptedNum.setText(str(attempted) + "/" + str(total) + "\t")
        correctNum.setText(str(correct) + "/" + str(total) + "\t")

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

        for error in errorsList :
            for desc in errorDesc[1:] :
                if error[0]==desc[0] :
                    error[0] = desc[1]
                    break

        wrong = attempted - correct

        '''
        errorCol1 = QVBoxLayout()
        errorCol2 = QVBoxLayout()
        errorCol3 = QVBoxLayout()
        '''
          
        prevLay = errorBars.layout()
        
        def clearLay(lay) :
            prevCount = lay.count()
            for i in range (0,prevCount) :
                child = lay.takeAt(0)
                if child.widget() is not None :
                    child.widget().deleteLater()
                elif child.layout() is not None :
                    clearLay(child.layout())
                    
        clearLay(prevLay)

        for i in range(0,len(errorsList)) :
            errorLab = QLabel()
            font = errorLab.font()
            font.setPointSize(18)
            errorLab.setFont(font)
            errorLab.setText(str(errorsList[i][0]))
            errorBars.layout().addWidget(errorLab,i,0)

            errorNum = QLabel()
            font = errorNum.font()
            font.setPointSize(18)
            errorNum.setFont(font)
            errorNum.setText("\t" + str(errorsList[i][1]))
            errorBars.layout().addWidget(errorNum,i,1)

            errorBar = QProgressBar()
            errorBar.setFixedHeight(30)
            errorBar.setTextVisible(False)
            errorBar.setMaximum(wrong)
            errorBar.setValue(int(errorsList[i][1]))
            errorBars.layout().addWidget(errorBar,i,2)
        
        '''
        errorBars.layout().addLayout(errorCol1)
        errorBars.layout().addLayout(errorCol2)
        errorBars.layout().addLayout(errorCol3)
        '''
        #QScrollArea().setLayout(errorBars.layout())
        #QObjectCleanupHandler().add(errorBars.layout())
        
        
        print student
        print errorsList
        

class QDropDown() :
    def __init__(self, entries) :
        self.entries = entries 
        self.dropdown = QComboBox()
        self.dropdown.addItems(self.entries)
        font = self.dropdown.font()
        font.setPointSize(32)
        self.dropdown.setFont(font)
        self.dropdown.setEditable(True)
        self.dropdown.lineEdit().setReadOnly(True)
        self.dropdown.lineEdit().setAlignment(Qt.AlignCenter)
        self.dropdown.currentIndexChanged.connect(entryChange)        


if __name__ == '__main__':
    #Get Response Files And Remove The .csv Extension
    respOffline = [name[:len(name)-4] for name in os.listdir("../Responses")]

    app = QApplication(sys.argv)
    resWind = QWidget()
  
    '''
    
    studSelect = QComboBox()
    studSelect.addItems(["Select  Student  ID"] + [str(i) for i in range(1,64)])
    font = studSelect.font()
    font.setPointSize(32)
    studSelect.setFont(font)
    studSelect.setEditable(True)
    studSelect.lineEdit().setReadOnly(True)
    studSelect.lineEdit().setAlignment(Qt.AlignCenter)'''

    #Drop Down Menu For Student Roll Numbers
    studSelect = QDropDown(["Select  Student  ID"] + [str(i) for i in range(1,64)])

    #Drop Down Menu For Tests Conducted
    testSelect = QDropDown(["Select  Test"] + respOffline)

    attemptedLab = QLabel()
    font = attemptedLab.font()
    font.setPointSize(24)
    attemptedLab.setFont(font)
    attemptedLab.setText("Attempted\t")
    
    correctLab = QLabel()
    font = correctLab.font()
    font.setPointSize(24)
    correctLab.setFont(font)
    correctLab.setText("Correct\t")

    attemptedNum = QLabel()
    font = attemptedNum.font()
    font.setPointSize(24)
    attemptedNum.setFont(font)
    attemptedNum.setText("0/0\t")

    correctNum = QLabel()
    font = correctNum.font()
    font.setPointSize(24)
    correctNum.setFont(font)
    correctNum.setText("0/0\t")

    attemptedBar = QProgressBar()
    attemptedBar.setFixedHeight(40)
    attemptedBar.setTextVisible(False)
    attemptedBar.setValue(0)
    correctBar = QProgressBar()
    correctBar.setFixedHeight(40)
    correctBar.setTextVisible(False)
    correctBar.setValue(0)

    errorBars = QScrollArea()
    errorBars.setWidgetResizable(True)

    errorBox = QGridLayout()
    errorBars.setLayout(errorBox)

    errorLab = QLabel()
    font = errorLab.font()
    font.setPointSize(24)
    errorLab.setFont(font)
    errorLab.setText("Analysis Of Errors")

    resBox = QVBoxLayout()
    optBox = QHBoxLayout()
    #hbox.addStretch()
    optBox.addWidget(studSelect.dropdown)
    optBox.addWidget(testSelect.dropdown)
    #hbox.addStretch()

    resBox.addLayout(optBox)
    resBox.addWidget(QLabel()) #Dummy Space

    scoreBox = QHBoxLayout()
    
    scoreLabs = QVBoxLayout()
    scoreLabs.addWidget(attemptedLab)
    scoreLabs.addWidget(correctLab)

    scoreNums = QVBoxLayout()
    scoreNums.addWidget(attemptedNum)
    scoreNums.addWidget(correctNum)

    scoreBars = QVBoxLayout()
    scoreBars.addWidget(attemptedBar)
    scoreBars.addWidget(correctBar)
    
    scoreBox.addLayout(scoreLabs)
    scoreBox.addLayout(scoreNums)
    scoreBox.addLayout(scoreBars)

    resBox.addLayout(scoreBox)
    resBox.addWidget(QLabel()) #Dummy Space

    resBox.addWidget(errorLab)
    resBox.addWidget(errorBars)
    
    resWind.setLayout(resBox)
    
    resWind.showFullScreen()

    resWind.setWindowTitle("Result Generator")
    resWind.show()

      
    sys.exit(app.exec_())

    
    
