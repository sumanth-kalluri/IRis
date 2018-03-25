Instructions To Run

Dependencies :
tiny_IRremote (For IR Communication on ATtiny85)
IRremote (For IR Communication on Arduino Uno)

The code in "Slave Side" Folder should be burnt to ATtiny85 (with 8Mhz Clock Rate)

The code in "Master Side/IR Interface (Arduino)" Folder should be burnt to Arduino Uno

The folder "Master Side/Hub (Raspberry Pi)" should be copied to Raspberry Pi.

"Master Side/Hub (Raspberry Pi)/Operation Files" contains the python scripts which drive IRis on Raspberry Pi

"Master Side/Hub (Raspberry Pi)/Responses" stores the responses given by students

"Master Side/Hub (Raspberry Pi)/Question Sets" stores the question sets for quizzes

"Master Side/Hub (Raspberry Pi)/Operation Files/serverSync.py" handles the server synchronization and should be added to startup files.

"Master Side/Hub (Raspberry Pi)/IRis-Launcher" invokes the python script in the "Master Side/Hub (Raspberry Pi)/Operation Files" Folder

Hardware Designs can be found in the schematics folder and in the Arduino codes
