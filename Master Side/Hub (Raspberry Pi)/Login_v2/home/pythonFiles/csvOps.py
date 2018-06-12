
'''
 * Project          : IRis
 * Purpose          : HackFest 2018 - The Annual 36 Hour Hackathon At IIT (ISM) Dhanbad
 * Module           : Master Hub (RasPi) [CSV Operations Module]
 ---------------------------------------------------------------------------------------
 * Platform         : Raspberry Pi 3B [running Raspbian 9 (Stretch)]
 * Serial Device    : Arduino Uno R3 (via USB)
 ---------------------------------------------------------------------------------------
 * Author           : Nishad Mandlik
 * Organization     : RoboISM - The Robotics Club Of IIT (ISM) Dhanbad
 * 
'''

def csvRead(fileURL):
    csvFile = open(fileURL, "r")

    #Convert CSV Cells To Elements Of Multidimensional List
    csvList = [csvLine.split(',') for csvLine in csvFile.read().splitlines()]
    
    csvFile.close()
    return csvList


def csvWrite(fileURL, csvList):
    csvFile = open(fileURL, "w")
    for csvRow in csvList :
        #Convert List To CSV Cells
        csvFile.write(','.join(csvRow) + '\n')
    csvFile.close()
    
