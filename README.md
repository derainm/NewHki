![Image Description](https://github.com/derainm/NewHki/blob/main/hkiIcon.svg)
It's a tools that allow you to edit your Aoe2 hki.
![Image Description](https://github.com/derainm/NewHki/blob/main/NewHki.PNG)
First i tryed to use this https://aokhotkeys.appspot.com/

the issue is the .hki structure change with Aoe2 patch and v16RC 

(community asking to add hotkeys all time) so the owner need to update every time.
<img width="637" height="98" alt="image" src="https://github.com/user-attachments/assets/5bbf0031-d2c0-4993-bf03-faef496d28a8" />

When we stoped to add new hotkeys, i try to contact crimsoncantab to update but he had issue with python version:
<img width="1056" height="220" alt="image" src="https://github.com/user-attachments/assets/f5ae7907-db5b-42af-83c6-65a4914756c3" />
So i just read genie-hki and found the logic to .hki file:
https://github.com/genie-js/genie-hki

**Analyze:**
- gzinflate .hki file to decompress the data
- Use genie-hki struct and only edite language id that we found on Hotkey.xml (v16rc or Aoe2 patch):
  ```javascript
                          ['key', t.int32],//-4
                          ['stringId', t.int32],//0
                          ['ctrl', t.bool],//1
                          ['alt', t.bool],//2
                          ['shift', t.bool],//3
                          ['mouse', t.int8]//4
  ```
Adapte to php.
 
- Use js and html to do a key board to that allow user to edit VK key hex code.
- Re compress the date gzdeflate

Last thing to test is keyboard compatibility special caractere : * / ( )   ... etc


The website can be accessed via this link:
https://aoe2recanalyst.byethost16.com/hotkeyEditor

