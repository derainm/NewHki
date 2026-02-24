
    document.getElementById('kb-toggle-btn').addEventListener('click', function() {
    const kb = document.getElementById('kb-container');
    const grid = document.querySelector('.master-grid');
    
    // Toggle Keyboard
    kb.classList.toggle('hidden');
    
    // Toggle Grid Size
    grid.classList.toggle('full-height');
    
    // Change Button Symbol
    if (kb.classList.contains('hidden')) {
        this.innerText = "+";
    } else {
        this.innerText = "-";
    }
});


const grid = document.getElementById('kb-grid');
let activeInput = null; 

const stickyModifiers = {
    CTRL: false,
    SHIFT: false,
    ALT: false
};

function setupInputTracking() {
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('shortcut-input')) {
            activeInput = e.target;
            document.querySelectorAll('.shortcut-input').forEach(i => i.style.outline = "none");
            activeInput.style.outline = "2px solid #007bff";
        }
    });
}
function setShortcutValue(mainKey) {
    if (!activeInput) return;
    
    let combo = [];
    if (stickyModifiers.CTRL) combo.push("CTRL");
    if (stickyModifiers.ALT) combo.push("ALT");
    if (stickyModifiers.SHIFT) combo.push("SHIFT");
    
    combo.push(mainKey);
    const finalValue = combo.join(" + ");
    
    // Update visual value
    activeInput.value = finalValue;



            const reverseKeys = {};
            for (const [hex, name] of Object.entries(specialKeys)) {
                reverseKeys[name] = hex; 
            }


/*
    // Update the NAME attribute
    if (activeInput.name) {
        let parts = activeInput.name.split('-');
        if (parts.length >= 3) {
            parts[2] = finalValue;
            activeInput.name = parts.join('-');
            console.log("New Name:", activeInput.name); // Check your console!
        }
    }
  */  
if (activeInput.name) {
    let parts = activeInput.name.split('-');
    if (parts.length >= 3) {
        const vkCode = stickyModifiers.keyCode || stickyModifiers.which;
        
        // 1. Try to get hex from our map (e.g., reverseKeys["F3"] -> "115")
        // 2. If not in map, use the raw vkCode
        let rawValue = reverseKeys[stickyModifiers.key] || vkCode;

        // Convert the numeric value to your specific 8-char hex format
        let finalValuee = formatVkToHex(rawValue);
         

/*

                          ['key', t.int32],//-4
                          ['stringId', t.int32],//0
                          ['ctrl', t.bool],//1
                          ['alt', t.bool],//2
                          ['shift', t.bool],//3
                          ['mouse', t.int8]//4

            $key = $result = ['key'];
            $ctrl = $result = ['ctrl'];
            $alt = $result = ['alt'];
            $shift = $result = ['shift'];
            $keyVal =  $key . '-' .  $ctrl . '-' . $alt  . '-' .  $shift;
*/

// $langId .'-' . $target_pos  .'-' .  $key . '-' .  $ctrl . '-' . $alt  . '-' .  $shift;

 
        parts[2] = '00000000';//$key
        parts[3] = '00';      //$ctrl
        parts[4] = '00';      //$alt 
        parts[5] = '00';      //$shift
        if(stickyModifiers.ctrlKey)
             parts[3] = '01';//01000000 
         if(stickyModifiers.altKey)
             parts[4] = '01';
         if(stickyModifiers.shiftKey)
             parts[5] = '01';  


        parts[2] = finalValuee;
        activeInput.name = parts.join('-');
    }
}




    localStorage.setItem(activeInput.id, finalValue);
    resetStickyModifiers();
}
/*
// Helper to update the input value
function setShortcutValue(mainKey) {
    if (!activeInput) return;
    
    let combo = [];
    if (stickyModifiers.CTRL) combo.push("CTRL");
    if (stickyModifiers.ALT) combo.push("ALT");
    if (stickyModifiers.SHIFT) combo.push("SHIFT");
    
    combo.push(mainKey);
    
    const finalValue = combo.join(" + ");
    activeInput.value = finalValue;
 
    
    localStorage.setItem(activeInput.id, finalValue);
    
    // Auto-reset sticky modifiers after a mouse scroll or key click
    resetStickyModifiers();
}
*/
const symbolMap = { "Backquote": "`", "Minus": "-", "Equal": "=", "BracketLeft": "[", "BracketRight": "]", "Backslash": "\\", "Semicolon": ";", "Quote": "'", "Comma": ",", "Period": ".", "Slash": "/", "Digit1": "1", "Digit2": "2", "Digit3": "3", "Digit4": "4", "Digit5": "5", "Digit6": "6", "Digit7": "7", "Digit8": "8", "Digit9": "9", "Digit0": "0" };

const physicalLayout = [
    ["Escape", "F1", "F2", "F3", "F4", "F5", "F6", "F7", "F8", "F9", "F10", "F11", "F12"],
    ["Backquote", "Digit1", "Digit2", "Digit3", "Digit4", "Digit5", "Digit6", "Digit7", "Digit8", "Digit9", "Digit0", "Minus", "Equal", "Backspace"],
    ["Tab", "KeyQ", "KeyW", "KeyE", "KeyR", "KeyT", "KeyY", "KeyU", "KeyI", "KeyO", "KeyP", "BracketLeft", "BracketRight", "Backslash"],
    ["CapsLock", "KeyA", "KeyS", "KeyD", "KeyF", "KeyG", "KeyH", "KeyJ", "KeyK", "KeyL", "Semicolon", "Quote", "Enter"],
    ["ShiftLeft", "KeyZ", "KeyX", "KeyC", "KeyV", "KeyB", "KeyN", "KeyM", "Comma", "Period", "Slash", "ShiftRight"],
    ["ControlLeft", "MetaLeft", "AltLeft", "Space", "AltRight", "ControlRight"]
];

async function init() {
    let layoutMap = null;
    if (navigator.keyboard?.getLayoutMap) layoutMap = await navigator.keyboard.getLayoutMap();

    physicalLayout.forEach(row => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'row';
        row.forEach(code => {
            const keyDiv = document.createElement('div');
            keyDiv.id = code;
            keyDiv.className = `key ${code}`;
            
            let label = layoutMap ? layoutMap.get(code) : null;
            if (!label) label = symbolMap[code] || code.replace('Key', '').replace('Left', '').replace('Right', '');
            keyDiv.innerText = label;

            keyDiv.addEventListener('mousedown', (e) => {
                e.preventDefault(); 
                if (!activeInput) return;

                if (code.includes('Control')) {
                    stickyModifiers.CTRL = !stickyModifiers.CTRL;
                    keyDiv.classList.toggle('active', stickyModifiers.CTRL);
                } else if (code.includes('Shift')) {
                    stickyModifiers.SHIFT = !stickyModifiers.SHIFT;
                    keyDiv.classList.toggle('active', stickyModifiers.SHIFT);
                } else if (code.includes('Alt')) {
                    stickyModifiers.ALT = !stickyModifiers.ALT;
                    keyDiv.classList.toggle('active', stickyModifiers.ALT);
                } else {
                    const mainKey = label.toUpperCase() === " " ? "SPACE" : label.toUpperCase();
                    setShortcutValue(mainKey);
                    
                    keyDiv.classList.add('active');
                    setTimeout(() => keyDiv.classList.remove('active'), 150);
                }
            });
            rowDiv.appendChild(keyDiv);
        });
        grid.appendChild(rowDiv);
    });
    setupInputTracking();
}

// --- MOUSE WHEEL HANDLING ---
window.addEventListener('wheel', (e) => {
    if (!activeInput) return;

    // Check if the wheel event is happening over the keyboard or an input
    // This avoids accidentally changing shortcuts while scrolling the page
    if (e.target.closest('.keyboard-case') || e.target.classList.contains('shortcut-input')) {
        e.preventDefault();
        
        // Use physical keys OR sticky virtual keys
        const isCtrl = e.ctrlKey || stickyModifiers.CTRL;
        const isAlt = e.altKey || stickyModifiers.ALT;
        const isShift = e.shiftKey || stickyModifiers.SHIFT;

        let combo = [];
        if (isCtrl) combo.push("CTRL");
        if (isAlt) combo.push("ALT");
        if (isShift) combo.push("SHIFT");

        //const direction = e.deltaY < 0 ? "WHEELUP" : "WHEELDOWN";
        const direction = e.deltaY < 0 ? "Mouse Wheel Up" : "Mouse Wheel Down";
        combo.push(direction);

        activeInput.value = combo.join(" + ");
        localStorage.setItem(activeInput.id, activeInput.value);
        




    let parts = activeInput.name.split('-');
    if (parts.length >= 3) {

    parts[2] = '00000000';//$key
    parts[3] = '00';      //$ctrl
    parts[4] = '00';      //$alt 
    parts[5] = '00';      //$shift

     // Convert the numeric value to your specific 8-char hex format
        let finalValuee =  '';
    //0xFF => 'Mouse Wheel Up',
    //0xFE => 'Mouse Wheel Down',
        if(e.deltaY < 0)
        {
            parts[2] ='FF';
        }
        else
        { 
            parts[2] ='FE';
        }

        if(e.ctrlKey)
             parts[3] = '01';//01000000 
         if(e.altKey)
             parts[4] = '01';
         if(e.shiftKey)
             parts[5] = '01';  
 
        activeInput.name = parts.join('-');

    }

        // Flash visual feedback if scrolling over the keyboard
        resetStickyModifiers();
    }
}, { passive: false });

function resetStickyModifiers() {
    stickyModifiers.CTRL = false;
    stickyModifiers.SHIFT = false;
    stickyModifiers.ALT = false;
    document.querySelectorAll('.ControlLeft, .ControlRight, .ShiftLeft, .ShiftRight, .AltLeft, .AltRight')
            .forEach(k => k.classList.remove('active'));
}

// Physical Keyboard Events
/*
window.addEventListener('keydown', (e) => {
    const el = document.getElementById(e.code);
    if (el) el.classList.add('active');

    if (activeInput) {
        e.preventDefault();

    
        let combo = [];
        if (e.ctrlKey) combo.push("CTRL");
        if (e.altKey) combo.push("ALT");
        if (e.shiftKey) combo.push("SHIFT");
        
        const mainKey = e.key === " " ? "SPACE" : e.key.toUpperCase();
        if (!["CONTROL", "SHIFT", "ALT", "META"].includes(mainKey)) {
            combo.push(mainKey);
            activeInput.value = combo.join(" + ");
 
 

            localStorage.setItem(activeInput.id, activeInput.value);
        }
    }
});
*/

function stringToHex(str) {
    return str.split('')
        .map(char => char.charCodeAt(0).toString(16).padStart(2, '0'))
        .join('')
        .padEnd(8, '0');
            ;
}

const specialKeys = {
    0xFB: 'Extra Button 2', 0xFC: 'Extra Button 1', 0xFD: 'Middle Button',
    0xFF: 'Mouse Wheel Up', 0xFE: 'Mouse Wheel Down',
    0x08: 'Backspace', 0x09: 'Tab', 0x0D: 'Enter', 0x10: 'Shift',
    0x11: 'Control', 0x12: 'Alt', 0x13: 'Pause', 0x14: 'CapsLock',
    0x1B: 'Escape', 0x20: 'Space', 0x21: 'PageUp', 0x22: 'PageDown',
    0x23: 'End', 0x24: 'Home', 0x2E: 'Delete', 0x2C: 'PrintScreen', 0x2D: 'Insert',
    0x25: 'ArrowLeft', 0x26: 'ArrowUp', 0x27: 'ArrowRight', 0x28: 'ArrowDown',
    0x60: 'Numpad0', 0x61: 'Numpad1', 0x62: 'Numpad2', 0x63: 'Numpad3',
    0x64: 'Numpad4', 0x65: 'Numpad5', 0x66: 'Numpad6', 0x67: 'Numpad7',
    0x68: 'Numpad8', 0x69: 'Numpad9', 0x6A: 'NumpadMultiply', 0x6B: 'NumpadAdd',
    0x6D: 'NumpadSubtract', 0x6E: 'NumpadDecimal', 0x6F: 'NumpadDivide',
    0x70: 'F1', 0x71: 'F2', 0x72: 'F3', 0x73: 'F4', 0x74: 'F5', 0x75: 'F6',
    0x76: 'F7', 0x77: 'F8', 0x78: 'F9', 0x79: 'F10', 0x7A: 'F11', 0x7B: 'F12',
    0xBA: ';', 0xBB: '=', 0xBC: ',', 0xBD: '-', 0xBE: '.', 0xBF: '/',
    0xC0: '`', 0xDB: '[', 0xDC: '\\', 0xDD: ']', 0xDE: "'",
    0x5B: 'MetaLeft', 0x5C: 'MetaRight', 0x5D: 'ContextMenu', 0x36: '6'
};


function formatVkToHex(vk) {
    // Ensure we have a number
    const num = parseInt(vk);
    // Convert to hex, uppercase, pad to 2 chars (e.g., "73")
    const hex = num.toString(16).toUpperCase().padStart(2, '0');
    // Pad with zeros to reach 8 characters (73 -> 73000000)
    return hex.padEnd(8, '0');
}


window.addEventListener('keydown', (e) => {
    const el = document.getElementById(e.code);
    if (el) el.classList.add('active');

    if (activeInput) {
        e.preventDefault();

        let combo = [];
        if (e.ctrlKey) combo.push("CTRL");
        if (e.altKey) combo.push("ALT");
        if (e.shiftKey) combo.push("SHIFT");
        
        const mainKey = e.key === " " ? "SPACE" : e.key.toUpperCase();

        // Only update if it's a "Main Key" (not just pressing Ctrl by itself)
        if (!["CONTROL", "SHIFT", "ALT", "META"].includes(mainKey)) {
            combo.push(mainKey);
            const finalValue = combo.join(" + ");
            activeInput.value = finalValue;



            const reverseKeys = {};
            for (const [hex, name] of Object.entries(specialKeys)) {
                reverseKeys[name] = hex; 
            }

/*

                          ['key', t.int32],//-4
                          ['stringId', t.int32],//0
                          ['ctrl', t.bool],//1
                          ['alt', t.bool],//2
                          ['shift', t.bool],//3
                          ['mouse', t.int8]//4

            $key = $result = ['key'];
            $ctrl = $result = ['ctrl'];
            $alt = $result = ['alt'];
            $shift = $result = ['shift'];
            $keyVal =  $key . '-' .  $ctrl . '-' . $alt  . '-' .  $shift;
*/

// $langId .'-' . $target_pos  .'-' .  $key . '-' .  $ctrl . '-' . $alt  . '-' .  $shift;
if (activeInput.name) {
    let parts = activeInput.name.split('-');
    if (parts.length >= 3) {
        const vkCode = e.keyCode || e.which;
        
        // 1. Try to get hex from our map (e.g., reverseKeys["F3"] -> "115")
        // 2. If not in map, use the raw vkCode
        let rawValue = reverseKeys[e.key] || vkCode;

        // Convert the numeric value to your specific 8-char hex format
        let finalValuee = formatVkToHex(rawValue);

        parts[2] = '00000000';//$key
        parts[3] = '00';      //$ctrl
        parts[4] = '00';      //$alt 
        parts[5] = '00';      //$shift
        if(e.ctrlKey)
             parts[3] = '01';//01000000 
         if(e.altKey)
             parts[4] = '01';
         if(e.shiftKey)
             parts[5] = '01';  


        parts[2] = finalValuee;
        activeInput.name = parts.join('-');
    }
}





            localStorage.setItem(activeInput.id, finalValue);
        }
    }
});

window.addEventListener('keyup', (e) => {
    const el = document.getElementById(e.code);
    if (el) el.classList.remove('active');
});

init();