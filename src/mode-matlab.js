define('ace/mode/matlab', function(require, exports, module) {

var oop = require("ace/lib/oop");
var TextMode = require("ace/mode/text").Mode;
var Tokenizer = require("ace/tokenizer").Tokenizer;
var MatlabHighlightRules = require("ace/mode/matlab_highlight_rules").MatlabHighlightRules;

var Mode = function() {
    this.$tokenizer = new Tokenizer(new MatlabHighlightRules().getRules());
};
oop.inherits(Mode, TextMode);

(function() {
    // Extra logic goes here. (see below)
}).call(Mode.prototype);

exports.Mode = Mode;
});

define('ace/mode/matlab_highlight_rules', function(require, exports, module) {

var oop = require("ace/lib/oop");
var TextHighlightRules = require("ace/mode/text_highlight_rules").TextHighlightRules;

var MatlabHighlightRules = function() {

    this.$rules = {
    "start" : [
        {
            token : "comment",
            regex : "\%.*$"
        },{
            token : "string",
            regex : '["](?:(?:\\\\.)|(?:[^"\\\\]))*?["]'            
        },{
            token : "string",
            regex : "['](?:(?:\\\\.)|(?:[^'\\\\]))*?[']"
        },{
            token : "keyword",
            regex : "(?:function|if|for|end|else|elseif|break|while|return)\\b"
        }, {
            token : "constant.numeric",
            regex : "0[xX][0-9a-fA-F]+\\b"
        }, {
            token : "constant.numeric",
            regex : "[+-]?\\d+(?:(?:\\.\\d*)?(?:[eE][+-]?\\d+)?)?\\b"
        }, {
            token : "constant.language.boolean",
            regex : "(?:true|false)\\b"
        }]
    };
};

oop.inherits(MatlabHighlightRules, TextHighlightRules);

exports.MatlabHighlightRules = MatlabHighlightRules;
});
