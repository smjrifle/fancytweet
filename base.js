/* Javascript file for FancyText
 * CopyLeft Dipesh Acharya
 * You are not only allowed but also encouraged to download, modify and redistribute this script!
 */

function $(id) { //shorthand for document.getElementbyId()
    return document.getElementById(id);
}

function addScript(fl,callback){// adds the script f1 and calls the function callback
    //Get the first head tag
    var head = document.getElementsByTagName("head")[0];
    //get all script tags from head
    var scrpts=head.getElementsByTagName('script');
    //Set Not loaded flag
    var nl=1;
    //Iterate through each script
    for (var i=0;i<scrpts.length;i++){
        //alert(scrpts[i].getAttribute('src'));
        //check if the existing script is the requested one
        //if yes set nl to 0
        if(scrpts[i].getAttribute('src')==fl) nl=0;
    }

    var tnode= document.createElement('script');
    tnode.type="text/javascript";
    //Check if any script matched the requested one to unset the flag
    if(nl){

        tnode.src=fl;
        head.appendChild(tnode);
    }
    //else callback();
    if( typeof eval(callback) == 'function' )
        tnode.onload=callback;
}


function init() {//the method to be triggered upon the loading of body
    //perform transformation, necesaary if a page with already text in form is refreshed or reloaded
    transformText();
}

function capitalize(str){// Returns: a copy of str with the first letter capitalized
    return str.charAt(0).toUpperCase() + str.substring(1);
}

function replaceChars(str, r) { //replace characters in str as described in replacement array r
    var newString = new String('');
    //find the length of the string
    var l = str.length;
    for (i = 0; i < l; i++) {
        //flag variable for replacement
        var f = 0;
        //loop through each key in the array r
        for (key in r) {
            //search and replace- search for key and replace with its corresponding value
            if (str.charAt(i) == key) {
                newString += r[key];
                //and set the flag
                f = 1;
            }
            //search and replace the opposite way - search for value and replace with its key
            else if (str.charAt(i) == r[key]) {
                newString += key;
                //set the flag
                f = 1;
            }

        }
        //if no matches yet, use the same old character
        if (!f) newString += str.charAt(i);
    }
    //return the converted string
    return newString;

}

function replaceExp(str, re) { //performs replacement on str based as described on re
    for (key in re) {
        //create new regular expression key, case insensitive one
        rx = new RegExp(key, 'i');
        //search for the regular expression in the string
        index = str.search(rx);
        //if the search finds something
        while (index != -1) {
            //Check if capital letter
            if (str.charAt(index) >= "A" && str.charAt(index) <= "Z") {
                //replace it with capitalized word
                str = str.replace(rx, capitalize(re[key]));
            }
            else {
                //perform the replacement
                str = str.replace(rx, re[key]);
            }
            //search for the next match
            index = str.search(rx);
        }
    }
    return str;
}

function toggleCheckBox(id){
    cb = $(id);
    if (cb.checked) cb.checked=false;
    else cb.checked=true;
}

function transformText() { //writes the transformed text
    //get the input value from the textarea
    var output = document.inputForm.inputText.value;
    //display or undisplay output division if there's input
    $('depends').style.display = ((output) ? '' : 'none');
    //display the loading image
    //$('inputCount').innerHTML='<img width="18px" height="18px" alt="" src="images/loading.gif">';
    //check if each option is selected and display help and facts accordingly
    if ($('nothing').checked) {
        output = output;
    }
    if ($('piratify').checked) {
        output = piratifyText(output);
        $('help-content').innerHTML = 'Talk like a pirate.';
    }
    if ($('jumble').checked) {
        output = jumbleText(output);
        $('help-content').innerHTML = 'Jumbles words keeping the first and last letter intact. "Arocdnicg to rsceearch at Cmabrigde Uinervtisy, it deosn\'t mttaer in waht oredr the ltteers in a wrod are, the olny iprmoatnt tihng is taht the frist and lsat ltteer are in the rghit pcale. The rset can be a toatl mses and you can sitll raed it wouthit pobelrm. Tihs is buseace the huamn mnid deos not raed ervey lteter by istlef, but the wrod as a wlohe."';
    }
    if ($('shrink').checked) {
        output = shrinkText(output);
        $('help-content').innerHTML = 'Shrinks words and phrases into informal slangs. Pretty useful for generating text for SMSes and microblogs as this reduces the length of the text.';
    }
    if ($('greekify').checked) {
        output = greekifyText(output);
        $('help-content').innerHTML = 'Transforms English alphabets into Greek alphabets. Did You Know : Greek had the first complete alphabet sets with vowels and consonants!';
    }
    if ($('leet').checked) {
        output = getLeetText(output);
        $('help-content').innerHTML = 'Uses Leetspeak/Leet/Elite text.';
    }
    if ($('crazy').checked) {
        output = getCrazyText(output);
        $('help-content').innerHTML = 'Transforms text using weird/crazy looking characters';
    }
    if ($('diacritic').checked) {
        output = getDiacriticText(output);
        $('help-content').innerHTML = 'Uses characters with diacritic marks like glyphs added.';
    }
    if ($('bubble').checked) {
        output = getBubbleText(output);
        $('help-content').innerHTML = 'Transforms characters into bubbled-text, enclosed within circles.';
    }
    //flipping has two other sub-options
    if ($('flip').checked) {
        $('help-content').innerHTML = 'Flips text up-side down or backwards. ¿ʇı ǝsnqɐ ʇ,uɐɔ noʎ ɟı ǝpoɔıun sı pooƃ ʇɐɥʍ';
        //enable the check-boxes
        $('back').disabled = false;
        $('down').disabled = false;
        if ($('back').checked) output = revertText(output);
        if ($('down').checked) output = flipText(output);
    } else {
        //disable the check-boxes
        $('back').disabled = true;
        $('down').disabled = true;
    }
    //write the output
    $('output').value = output;
    //and also generate links
    //insertLinks(output);
    //count the letters
    countLetters();

}

function countLetters(){//counts the letters in input and output
    //count the letters in input textarea
    $('inputCount').innerHTML='('+$('input').value.length+')';
    //if there's no input, hide the count
    if (!$('input').value) {
        $('inputCount').innerHTML='';
    }
    //count the letters in output textarea
    $('outputCount').innerHTML='('+$('output').value.length+')';
}

function piratifyText(str) { //Returns text in pirate language
    var re = {};
    //Create a replacement array
    r = {
        "my": "me",
        "boss": "admiral",
        "manager": "admiral",
        "captain": "Cap'n",
        "myself": "meself",
        "your": "yer",
        "you": "ye",
        "friend": "matey",
        "friends": "maties",
        "co[-]?worker": "shipmate",
        "co[-]?workers": "shipmates",
        "earlier": "afore",
        "old": "auld",
        "the": "th'",
        "of": "o'",
        "don't": "dern't",
        "do not": "dern't",
        "no": "nay",
        "yeah": "aye",
        "didn't": "dinna",
        "didn't know": "did nay know",
        "hadn't": "ha'nae",
        "wasn't": "weren't",
        "haven't": "ha'nae",
        "for": "fer",
        "bit": "tis",
        "lady": "wench",
        "wife": "lady",
        "girl": "lass",
        "girls": "lassies",
        "guy": "lubber",
        "man": "lubber",
        "fellow": "lubber",
        "dude": "lubber",
        "boy": "lad",
        "boys": "laddies",
        "children": "little sandcrabs",
        "kids": "minnows",
        "him": "that drunken sailor",
        "was": "were bein",
        "Hey": "Avast",
        "her": "that lovely lass",
        "money": "dubloons",
        "dollars": "pieces of eight",
        "cents": "shillings",
        "road": "sea",
        "street": "river",
        "streets": "rivers",
        "highway": "highways",
        "interstate": "high sea",
        "probably": "likely",
        "idea": "notion",
        "car": "boat",
        "cars": "boats",
        "truck": "schooner",
        "trucks": "schooners",
        "SUV": "ship",
        "airplane": "flying machine",
        "jet": "flying machine",
        "machine": "contraption",
        "driving": "sailing",
        "understand": "reckon",
        "drive": "sail",
        "died": "snuffled it",
        "mad": "addled",
        "insane": "addled",
        "stupid": "addled",
        "fool": "addlepate",
        "hello": "Ahoy!",
        "hey": "Avast!",
        "By God": "Begad",
        "Stop": "Belay",
        "nonsense": "bilge",
        "foolish": "bilge",
        "loot": "booty",
        "pirate": "buccaneer",
        "pirates": "buccaneers",
        "chant": "shantey",
        "sword": "cutlass",
        "food": "grub",
        "where is": "whar be",
        "is that": "be that",
        "any": "some godforsaken",
        "nearby": "broadside",
        "attractive": "comely",
        "happy": "grog-filled",
        "restaurant": "galley",
        "bank": "buried treasure",
        "I would like to": "I be needin' t'",
        "I desire": "I've a fierce fire in me belly t'",
        "I wish I knew how to": "I be hankerin' t'",
        "find": "come across",
        "excuse me": "arrr!",
        "madam": "proud beauty",
        "miss": "comely wench",
        "am": "be",
        "tell": "be tellin'",
        "know": "be knowin'",
        "how far": "how many leagues",
        "yes": "aye",
        "clean": "swab",
        "moron": "swab",
        "asshole": "scurvy swab",
        "sucker": "swab",
        "merchant": "sutler",
        "telescope": "spyglass",
        "liver": "lights",
        "quick": "smart",
        "quickly": "handsomely",
        "Goodbye": "Fair Winds!",
        "Bye": "Godspeed",
        "Good luck": "Fair Winds!",
        "after": "aft",
        "between": "beetwitx",
        "around": "aroun'"

    };
    //Add word boundary
    for (key in r) {
        re["\\b" + key + "\\b"] = r[key];
    }
    //Make the replacements
    str = replaceExp(str, re);

    //For the case sensitive ones
    r = {
        "He": "The ornery cuss",
        "She": "The winsome lass",
        "he": "he be",
        "she": "she be"
    };
    //Add word boundary
    for (key in r) {
        re["\\b" + key + "\\b"] = r[key];
    }
    //Make the replacements
    for (key in re) {
        rx = new RegExp(key, 'g');
        str = str.replace(rx, re[key]);
    }

    //For the case sensitive ones
    r = {
        "ing\\b": "in'",
        "ings\\b": "in's"
    };
    //Add word boundary
    //Make the replacements
    for (key in r) {
        rx = new RegExp(key, 'g');
        str = str.replace(rx, r[key]);
    }

    return str;
}

function jumbleWord(word) { //jumbles each word
    //get the length of the word
    var l = word.length;
    //if the word has length less than 4 charcters, return without any change
    if (l < 4) return word;
    //crate new word, array of characters
    var newWord = new Array();
    //the first character has to be intact
    newWord[0] = word.charAt(0);
    //and same with the last character
    newWord[l - 1] = word.charAt(l - 1)

    //randomly exchange other characters, from second to second-last
    for (var j = 1; j < l - 1; j++) {
        //only if character is not already placed in new word
        if (!newWord[j]) {
            while (1) { //loop until break
                //randomly generate a value and round it off
                var t = Math.floor(Math.random() * (l - 2 - j + 1)) + j;
                //perform the exchange of letters
                if (!newWord[t]) {
                    newWord[j] = word.charAt(t);
                    newWord[t] = word.charAt(j);
                    break;
                }
            }

        }
    }
    //append the characters to generate new jumbled word
    var jumbledWord = new String('');
    for (var k = 0; k < l; k++) {
        jumbledWord = jumbledWord + newWord[k];
    }

    return jumbledWord;
}

function jumbleText(s) {//jumbles string with words
    //search for words using regular expression
    var words = s.match(/(\w[^ ,.!?\n]*)/g);
    //loop through each
    for (x in words) {
        //and replace
        s = s.replace(words[x], jumbleWord(words[x]));
    }
    //return the string with jumbled words
    return s;
}

function shrinkText(str) {//returns shortened informal slang words
    var re = {};
    //Create a replacement array
    r = {
        "you": "u",
        "your": "ur",
        "before": "b4",
        "after": "af8r",
        "tomorrow": "2moro",
        "have": "ve",
        "don't": "dont",
        "friend": "fren",
        "friends": "frens",
        "better": "b8r",
        "oh": "o",
        "too": "2",
        "to": "2",
        "busy": "bg",
        "today": "2day",
        "together": "2gether",
        "do not": "dont",
        "have not": "vent",
        "yeah": "ya",
        "didn't": "didnt",
        "did not": "didnt",
        "hadn't": "hadnt",
        "wasn't": "wasnt",
        "haven't": "vent",
        "for": "4",
        "to you": "2u",
        "as a matter of fact": "AAMOF",
        "any day now": "ADN",
        "girl": "gal",
        "girls": "gals",
        "as far as i know": "AFAIK",
        "away from keyboard": "AFK",
        "also known as": "AKA",
        "as soon as possible": "ASAP",
        "boy friend": "bf",
        "boy fren": "bf",
        "girl friend": "gf",
        "girl fren": "gf",
        "be right back": "BRB",
        "be": "b",
        "by the way": "BTW",
        "the": "da",
        "see": "c",
        "see you": "CU",
        "fuck": "f*",
        "see ya": "CYa",
        "do not disturb": "DND",
        "don't disturb": "DND",
        "dont disturb": "DND",
        "for your information": "FYI",
        "great": "gr8",
        "have a nice day": "HAND",
        "hate": "h8",
        "love": "luv",
        "loves": "luvs",
        "i dont know": "IDK",
        "i don't know": "IDK",
        "i do not know": "IDK",
        "later": "l8r",
        "laughing my ass off": "LMAO",
        "laughing my fucking ass off": "LMFAO",
        "Microsoft": "MS",
        "laugh out lod": "LOL",
        "laughing out lod": "LOL",
        "mind your own business": "MYOB",
        "mate": "m8",
        "one": "1",
        "two": "2",
        "three": "3",
        "four": "4",
        "five": "5",
        "six": "6",
        "seven": "7",
        "eight": "8",
        "nine": "9",
        "ten": "10",
        "eleven": "11",
        "anyone": "NE1",
        "no problem": "NP",
        "no problems": "NP",
        "oh i see": "OIC",
        "oh, i see": "OIC",
        "oh, my god": "OMG",
        "oh my god": "OMG",
        "o, my god": "OMG",
        "o my god": "OMG",
        "operating system": "OS",
        "internet explorer": "IE",
        "on the phone": "OTP",
        "peer to peer": "P2P",
        "please": "pls",
        "point of view": "POV",
        "people": "ppl",
        "really": "rly",
        "rolling on the floor laughing": "ROFL",
        "rolling on floor laughing": "ROFL",
        "regarding your comment": "RYC",
        "thanks": "thx",
        "thanx": "thx",
        "thank you": "thnku",
        "take your time": "TYT",
        "with": "w/",
        "without": "w/",
        "what the fuck": "WTF",
        "what the heck": "WTH",
        "wait": "w8",
        "why": "y",
        "right": "rite",
        "dollar": "$",
        "dollars": "$",
        "about": "abt",
        "happy": ":)",
        "sad": ":-(",
        "smile": ":-)",
        "in my opinion": "IMO",
        "in my humble opinion": "IMHO",
        "some": "sum",
        "someone": "sum1",
        "noone": "no1",
        "birthday": "bday",
        "college": "clz",
        "information": "info",
        "technology": "tech",
        "forever": "4evr",
        "forget": "4get",
        "forgive": "4give",
        "forgot": "4got",
        "life": "lyf",
        "from": "4m",
        "forward": "4wad",
        "best friends forever": "BFF",
        "and": "&",
        "yes": "s",
        "for you": "4u",
        "sucker": "sckr",
        "will": "ll",
        "you will": "u'll",
        "they will": "they'll",
        "should": "shd",
        "should not": "shdnt",
        "shouldn't": "shdnt",
        "good": "gud",
        "wouldn't": "wudnt",
        "would not": "wudnt",
        "first": "1st",
        "second": "2nd",
        "third": "3rd",
        "fourth": "4th",
        "fifth": "5th",
        "sixth": "6th",
        "seventh": "7th",
        "eighth": "8th",
        "ninth": "9th",
        "tenth": "10th",
        "am": "m",
        "hello": "hlw",
        "he is": "he's",
        "she is": "c's",
        "he has": "he's",
        "she has": "c's",
        "doing": "doin",
        "okay": "OK",
        "Good luck": "Fair Winds!",
        "i would": "i'd",
        "he would": "he'd",
        "she would": "c'd",
        "they would": "they'd",
        "we would": "we'd",
        "between": "btwn",
        "would": "wud",
        "around": "aroun",
        "tonight": "2nite",
        "night": "nite",
        "what": "wat",
        "are": "r",
        "she": "c"

    };
    //Add word boundary and create string to be used for regular expressions
    for (key in r) {
        re["\\b" + key + "\\b"] = r[key];
    }

    //Make the replacements based on re
    str = replaceExp(str, re);


    //replace *ing with in
    r = {
        "ing\\b": "in",
        "ings\\b": "ins"
    };

    //Make the replacements
    for (key in r) {
        rx = new RegExp(key, 'g');
        str = str.replace(rx, r[key]);
    }
    //return the new string
    return str;

}

function revertText(str) { //returns string reverting them
    var revertedString = new String('');
    //perform the interchange
    for (i = str.length - 1; i >= 0; i--) {
        revertedString = revertedString + str.charAt(i);
    }
    //return the interchanged words
    return revertedString;
}

function flipText(str) { //return text with flipped characters
    //create the replacement array for inverted characters
    var r = {
        '\u0021': 'i',
        '\u0022': '\u201E',
        '\u0026': '\u214B',
        '\u0027': '\u002C',
        '\u0028': '\u0029',
        '\u002E': '\u02D9',
        '\u0033': '\u0190',
        '\u0034': '\u152D',
        '\u0036': '\u0039',
        '\u0037': '\u2C62',
        '\u003B': '\u061B',
        '\u003C': '\u003E',
        '\u003F': '?',
        '\u0041': '\u2200',
        '\u0042': '8',//B->8
        '\u0043': '\u2183',
        '\u0044': '\u15E1',//new
        '\u0045': '\u018E',
        '\u0046': '\u2132',
        '\u0047': '\u2141',
        '\u004A': '\u017F',
        '\u004B': '\u22CA',
        '\u004C': '\u2142',
        '\u004D': '\u0057',
        '\u004E': '\u1D0E',
        '\u0050': '\u0500',
        '\u0051': '\u038C',
        '\u0052': '\u1D1A',
        '\u0054': '\u22A5',
        '\u0055': '\u2229',
        '\u0056': '\u1D27',
        '\u0059': '\u2144',
        '\u005B': '\u005D',
        '\u005F': '\u203E',
        '\u0061': '\u0250',
        '\u0062': '\u0071',//b->q
        '\u0063': '\u0254',
        '\u0064': '\u0070',
        '\u0065': '\u01DD',
        '\u0066': '\u025F',
        '\u0067': '\u0183',
        '\u0068': '\u0265',
        '\u006A': '\u027E',
        '\u006B': '\u029E',
        '\u006C': '\u05DF',
        '\u006D': '\u026F',
        '\u006E': '\u0075',
        '\u0072': '\u0279',
        '\u0074': '\u0287',
        '\u0076': '\u028C',
        '\u0077': '\u028D',
        '\u0079': '\u028E',
        '\u007B': '\u007D',
        '\u203F': '\u2040',
        '\u2045': '\u2046',
        '\u2234': '\u2235'
    }
    //replace and return
    return replaceChars(str, r);

}

function greekifyText(str) { //returns text with greek characters
    //create the replacement array for english to greek alphabets
    var r = {
        'a': '\u03B1',
        'b': '\u03B2',
        'c': '\u03c2',
        'd': '\u03B4',
        'e': '\u03B5',
        'f': '\u03C6',
        'g': '\u03B3',
        'h': '\u03B7',
        'i': '\u03B9',
        'k': '\u03BA',
        'l': '\u03BB',
        'm': '\u03BC',
        'n': '\u03BD',
        'o': '\u03BF',
        'p': '\u03C0',
        'r': '\u03C1',
        's': '\u03C3',
        't': '\u03C4',
        'u': '\u03C5',
        'w': '\u03C9',
        'x': '\u03C7',
        'y': '\u03C8',
        'z': '\u03B6',
        'A': '\u0391',
        'B': '\u0392',
        'D': '\u0394',
        'E': '\u0395',
        'F': '\u03A6',
        'G': '\u0393',
        'H': '\u0397',
        'I': '\u0399',
        'K': '\u039A',
        'L': '\u039B',
        'M': '\u039C',
        'N': '\u039D',
        'O': '\u039F',
        'P': '\u03A0',
        'R': '\u03A1',
        'S': '\u03A3',
        'T': '\u03A4',
        'U': '\u03A5',
        'W': '\u03A9',
        'X': '\u03A7',
        'Y': '\u03A8',
        'Z': '\u0396'
    }
    //replace and return
    return replaceChars(str, r);
}

function getLeetText(str) { //returns text with elite characters
    //create the replacement array for leet
    var r = {
        'a': '@',
        'A': '4',
        'b': '8',
        'B': '8',
        'c': '(',
        'C': '(',
        'e': '3',
        'E': '3',
        'g': '9',
        'G': '9',
        'i': '1',
        'I': '1',
        'k': '|<',
        'K': '|<',
        'l': '|',
        'L': '|',
        'o': '0',
        'O': '0',
        'r': '\u042F',
        'R': '\u042F',
        's': '5',
        'S': '5',
        't': '7',
        'T': '7',
        'x': '><',
        'X': '><',
        'z': '2',
        'Z': '2'

    }
    //replace and return
    return replaceChars(str, r);
}

function getCrazyText(str) { //returns text with crazy/weird characters
    //create the replacement array for crazy text
    var r = {
        'a': '\u2206',
        'b': '\u03B2',
        'c': '\u03c2',
        'd': '\u2202',
        'e': '\u2211',
        'f': '\uFF93',
        'g': '\u0431',
        'h': '\uFF7B',
        'i': '\u2170',
        'j': '\uFF89',
        'k': '\u043A',
        'l': '\uFF9A',
        'm': '\u33A1',
        'n': '\u0438',
        'o': '\u25CA',
        'p': '\u3115',
        'q': '\u00B6',
        'r': '\u042F',
        's': '\u3105',
        't': '\u20AE',
        'u': '\u3129',
        'v': '\u221A',
        'w': '\u1FF3',
        'x': '\u2717',
        'y': '\u311A',
        'z': '\u0291',
        'A': '\u2206',
        'B': '\u03B2',
        'C': '\u03c2',
        'D': '\u2202',
        'E': '\u2211',
        'F': '\uFF93',
        'G': '\u0431',
        'H': '\uFF7B',
        'I': '\u2170',
        'J': '\uFF89',
        'K': '\u043A',
        'L': '\uFF9A',
        'M': '\u33A1',
        'N': '\u0438',
        'O': '\u25CA',
        'P': '\u3115',
        'Q': '\u00B6',
        'R': '\u042F',
        'S': '\u3105',
        'T': '\u20AE',
        'U': '\u3129',
        'V': '\u221A',
        'W': '\u1FF3',
        'X': '\u2717',
        'Y': '\u311A',
        'Z': '\u0291'
    }
    //replace and return
    return replaceChars(str, r);

}

function getDiacriticText(str) {
    //create the replacement array for diacritic letters (with glyphes)
    var r = {
        'a': '\u0103',
        'b': '\u0100',
        'c': '\u0107',
        'd': '\u010F',
        'e': '\u0113',
        'f': '\uFF93',
        'g': '\u0121',
        'h': '\u0125',
        'i': '\u0129',
        'j': '\u0135',
        'k': '\u0137',
        'l': '\u013A',
        'm': '\u33A1',
        'n': '\u0148',
        'o': '\u01D2',
        'p': '\u3115',
        'q': '\u00B6',
        'r': '\u0155',
        's': '\u015D',
        't': '\u0163',
        'u': '\u01D6',
        'v': '\u221A',
        'w': '\u0175',
        'x': '\u2717',
        'y': '\u0177',
        'z': '\u017C',
        'A': '\u0100',
        'B': '\u03B2',
        'C': '\u0108',
        'D': '\u010E',
        'E': '\u0114',
        'F': '\uFF93',
        'G': '\u011E',
        'H': '\u0124',
        'I': '\u0128',
        'J': '\u0134',
        'K': '\u0136',
        'L': '\u0139',
        'M': '\u33A1',
        'N': '\u0147',
        'O': '\u0150',
        'P': '\u3115',
        'Q': '\u00B6',
        'R': '\u0156',
        'S': '\u015C',
        'T': '\u0162',
        'U': '\u01D9',
        'V': '\u221A',
        'W': '\u0174',
        'X': '\u2717',
        'Y': '\u0178',
        'Z': '\u017B'
    }
    //replace and return
    return replaceChars(str, r);

}

function getBubbleText(str) { //returns text with circled characters
    //create the replacement array for bubble text
    var r = {
        'a': '\u24D0',
        'b': '\u24D1',
        'c': '\u24D2',
        'd': '\u24D3',
        'e': '\u24D4',
        'f': '\u24D5',
        'g': '\u24D6',
        'h': '\u24D7',
        'i': '\u24D8',
        'j': '\u24D9',
        'k': '\u24DA',
        'l': '\u24DB',
        'm': '\u24DC',
        'n': '\u24DD',
        'o': '\u24DE',
        'p': '\u24DF',
        'q': '\u24E0',
        'r': '\u24E1',
        's': '\u24E2',
        't': '\u24E3',
        'u': '\u24E4',
        'v': '\u24E5',
        'w': '\u24E6',
        'x': '\u24E7',
        'y': '\u24E8',
        'z': '\u24E9',
        'A': '\u24B6',
        'B': '\u24B7',
        'C': '\u24B8',
        'D': '\u24B9',
        'E': '\u24BA',
        'F': '\u24BB',
        'G': '\u24BC',
        'H': '\u24BD',
        'I': '\u24BE',
        'J': '\u24BF',
        'K': '\u24C0',
        'L': '\u24C1',
        'M': '\u24C2',
        'N': '\u24C3',
        'O': '\u24C4',
        'P': '\u24C5',
        'Q': '\u24C6',
        'R': '\u24C7',
        'S': '\u24C8',
        'T': '\u24C9',
        'U': '\u24CA',
        'V': '\u24CB',
        'W': '\u24CC',
        'X': '\u24CD',
        'Y': '\u24CE',
        'Z': '\u24CF',
        '0': '\u24EA',
        '1': '\u2460',
        '2': '\u2461',
        '3': '\u2462',
        '4': '\u2463',
        '5': '\u2464',
        '6': '\u2465',
        '7': '\u2466',
        '8': '\u2465',
        '9': '\u2466'
    }
    //replace and return
    return replaceChars(str, r);
}