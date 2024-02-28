import os
from tkinter import *

def restart():
    os.system('shutdown /r /t 1')
    
def restart_time():
    os.system('shutdown /r /t 20')
    
def logout():
    os.system('shutdown -1')
    
def shutdown():
    os.system('shutdown /s /t 1')

st = Tk()
st.title('Shutdown App')
st.geometry('500x500')
st.config(bg = 'Green')

restart_button  = Button(st, text = 'Restart' , font=('Times New Roman', 20 , 'bold'),relief=RAISED, cursor = 'Plus', command= restart)
restart_button.place(x = 150 , y = 60 , height= 40 , width=200)

restart__time_button  = Button(st, text = 'Restart Time' , font=('Times New Roman', 20 , 'bold'),relief=RAISED, cursor = 'Plus', command= restart_time)
restart__time_button.place(x = 150 , y = 170 , height= 40 , width=200)

logout_button  = Button(st, text = 'Logout' , font=('Times New Roman', 20 , 'bold'),relief=RAISED, cursor = 'Plus', command=logout)
logout_button.place(x = 150 , y = 270 , height= 40 , width=200)

shoudown_button  = Button(st, text = 'Shutdown' , font=('Times New Roman', 20 , 'bold'),relief=RAISED, cursor = 'Plus',command= shutdown)
shoudown_button.place(x = 150 , y = 370 , height= 40 , width=200)

st.mainloop()