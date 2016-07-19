/**
 * MaskFormattedTextField script used for mask/regexp operations.   Another option for InputTextMask
 * Mask Individual Character Usage:
 * 9 - designates only numeric values
 * L - designates only uppercase letter values
 * l - designates only lowercase letter values
 * A - designates only alphanumeric values
 * X - denotes that a custom client script regular expression is specified</li>
 * All other characters are assumed to be "special" characters used to mask the input component.
 * Example 1:
 * (999)999-9999 only numeric values can be entered where the the character
 * position value is 9. Parenthesis and dash are non-editable/mask characters.
 * Example 2:
 * 99L-ll-X[^A-C]X only numeric values for the first two characters,
 * uppercase values for the third character, lowercase letters for the
 * fifth/sixth characters, and the last character X[^A-C]X together counts
 * as the eighth character regular expression that would allow all characters
 * but "A", "B", and "C". Dashes outside the regular expression are non-editable/mask characters.
 * @constructor
 * @param (String) mask The InputTextMask
 * @param (boolean) clearWhenInvalid True to clear the mask when the field blurs and the text is invalid. Optional, default is true.
 */

Ext.form.MaskFormattedTextField = function(config){
  var defConfig = {
    placeholder: "_",
    autocomplete: 'off',
    mask: "(###)###-####", //default mask is a US 10 digit phone number
  };
  Ext.applyIf(config,defConfig);
  Ext.form.MaskFormattedTextField.superclass.constructor.call(this, config);
};
Ext.extend(Ext.form.MaskFormattedTextField, Ext.form.TextField,{

  initEvents : function(){
    Ext.form.MaskFormattedTextField.superclass.initEvents.call(this);
    this.el.on('keydown',this.mapKeyToBehavior,this);
    this.el.on("keypress", this.stopEventFunction,this); 
    this.el.on("keyup", this.stopEventFunction,this); 
    //this.el.on("focus", this.doSelection,this); //seems to be ignored
    //this.el.on("click", this.doSelection,this); //use this to highlight the first char
  },
        
  render: function(ct, position){
    if (!this.vtype) {
      var regexMask = this.mask.replace(/#/g,"\\d");
      regexMask = regexMask.replace(/(/,"\\(");
      regexMask = regexMask.replace(/)/,"\\)");
      this.regex = eval('/^'+regexMask+'$/');
      if (this.invalidMessage) {
        this.regexText = this.invalidMessage;
      } else {
        this.regexText = "This field has a required format of " + this.mask;
      }
    }
    Ext.form.MaskFormattedTextField.superclass.render.call(this,ct,position);
  },
        
  reset: function(ct, position){
    Ext.form.MaskFormattedTextField.superclass.reset.call(this);
    this.doSelection();
  },
    
    KEY_RANGES : { 
        numeric: [48, 57], 
        padnum: [96, 105], 
        characters: [65, 90], 
        all: [0, 255]
    },
        
    isInRange : function(charCode, range) { 
        return charCode >= range[0] && charCode <= range[1]; 
    }, 
     
  // TODO: support other wildcards, like: A (char or number), U (uppercase char), L (lowercase char), ' (escape) 
  getRange : function(wildcard) { 
    var temp = [];
        switch (wildcard) { 
            case '#': 
         temp.push(this.KEY_RANGES["numeric"]); 
         break;
            case '?': 
         temp.push(this.KEY_RANGES["characters"]); 
         break;
            case '*': 
         temp.push(this.KEY_RANGES["all"]); 
         break;
     default:
     return null;
        }
        return temp; 
    }, 
     
    isMaskChar : function(chr) { 
        return this.getRange(chr) != null; 
    },
     
    getDefaultString : function() { 
        var str = ""; 
        var mask = this.mask; 
        for(var i = 0; i < mask.length; i++) { 
            var chr = mask.charAt(i); 
            str += this.isMaskChar(chr) ? this.placeholder : chr; 
        } 
        return str; 
    },
     
    doBackspace : function() { 
        this.doDelete(); 
        this.moveCaret(-1); 
    },
     
    doDelete : function() { 
        var position = this.getCaretPosition().left; 

        var left = this.el.dom.value.substr(0, position); 
        var right = this.el.dom.value.substr(position + 1, this.el.dom.value.length - 1); 

        this.el.dom.value = left + this.placeholder + right; 
        this.setSelection(position); 
    },
     
    getCaretPosition : function() { 
        var left, right; 
         
        if (!this.el.dom) { 
            // unexpected 
            return null; 
        } 

        var fieldEl = this.el.dom; 
        if (fieldEl.createTextRange) { 
            var range = document.selection.createRange().duplicate(); 
            range.moveEnd("character", fieldEl.value.length); 

            if (!range.text) { 
                left = fieldEl.value.length; 
            } else { 
                left = fieldEl.value.lastIndexOf(range.text); 
            } 
             
            range = document.selection.createRange().duplicate(); 
            range.moveStart("character", -(fieldEl.value.length)); 

            right = range.text.length; 
        } else { 
            left = fieldEl.selectionStart; 
            right = fieldEl.selectionEnd; 
        } 

        return {left: left, right: right}; 
    },
     
    setSelection : function(leftPos, rightPos) { 
        var left = leftPos; 
        var right = rightPos || left + 1; 

        if (this.el.dom.createTextRange) { 
            var range = field.dom.createTextRange(); 
            range.moveStart("character", left); 
            range.moveEnd("character", right - this.el.dom.value.length); 
            range.select(); 
        } else { 
            this.el.dom.setSelectionRange(left, right); 
        } 
    },
     
    doMask : function(key) { 
        if (this.isInRange(key, this.KEY_RANGES["padnum"])) { 
            key -= 48; 
        }                
        var position = this.getCaretPosition().left; 
        //make sure a MaskChar is currently selected.
        //Prevents a possible error if more than one char is selected (such as when tabbing into a field).
        while(!(this.isMaskChar(this.mask.charAt(position))) && (position < this.mask.length)){
          position++; 
        }
        var ranges = this.getRange(this.mask.charAt(position));
        var valid = false; 
        for (var i = 0; i < ranges.length; i++) { 
            if (this.isInRange(key, ranges[i])) { 
                valid = true; 
                break; 
            } 
        } 

        if (valid) { 
            var fieldValue = this.el.dom.value; 
             
            var left = fieldValue.substr(0, position); 
            var right = fieldValue.substr((position + 1), (fieldValue.length - 1)); 
            this.el.dom.value = left + String.fromCharCode(key) + right; 
             
            var previousPosition = position; 
             
            do { 
                position++; 
            } while(!(this.isMaskChar(this.mask.charAt(position))) && (position < fieldValue.length)); 
             
            if (this.isMaskChar(this.mask.charAt(position))) { 
                this.setSelection(position); 
            } else { 
                this.setSelection(previousPosition); 
            } 
        } 
    },
     
    mapKeyToBehavior : function(evt) { 
        var key = evt.getKey(); 
        switch (key) { 
            case Ext.EventObject.BACKSPACE: 
                this.doBackspace(); 
                break; 
            case Ext.EventObject.DELETE: 
                this.doDelete(); 
                break; 
            case Ext.EventObject.HOME: 
                this.setSelection(0); 
                break; 
            case Ext.EventObject.END: 
                this.setSelection(this.el.dom.value.length - 1); 
                break; 
            case Ext.EventObject.RIGHT: 
                this.moveCaret(1); 
                break; 
            case Ext.EventObject.LEFT: 
                this.moveCaret(-1); 
                break; 
            case Ext.EventObject.TAB: 
            case Ext.EventObject.ENTER: 
                return; 
            default: 
                this.doMask(key); 
        } 
        evt.stopEvent(); 
    },
     
    doSelection : function(evt) { 
     if (!this.el.dom.value) { 
              this.el.dom.value = this.getDefaultString(); 
            } 
     var pos = 1;
         
        if(pos == this.el.dom.value.length) { 
            pos--; 
        } 

        if(!this.isMaskChar(this.mask.charAt(pos))) { 
            if (!this.moveCaret(1)) { 
                this.moveCaret(-1); 
            } 
        } else { 
            this.setSelection(pos); 
        } 
    },
     
    moveCaret : function(step, left) { 
        var position = left || this.getCaretPosition().left; 
         
        if (step == 0) { 
            return false; 
        } 
         
        if (position == 0 && step < 0) { 
            return false; 
        } 

        if ((position >= (this.el.dom.value.length - 1)) && (step > 0)) { 
            return false; 
        } 

        do { 
            position += step; 
        } while(!(this.isMaskChar(this.mask.charAt(position))) && (position > 0) && (position < this.el.dom.value.length)); 

        if (!(this.isMaskChar(this.mask.charAt(position)))) { 
            return false; 
        } 

        this.setSelection(position); 
        return true; 
    },

    stopEventFunction : function(evt) { 
                evt.stopEvent(); 
            },
 
});