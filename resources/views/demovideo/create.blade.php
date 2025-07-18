@extends('admin.layout')

@section('content')

<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #13acb4 !important;
    }
</style>



<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Demo Video</h1>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('demovideo')}}">Demo Video List</a></li>
                        <li class="breadcrumb-item active">Add Demo Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>

        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary my-select">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif

                        <div class="card-header color-me">
                            <h3 class="card-title">Add Demo Video</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('demovideo.add-video') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="group_name">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{old('title')}}" placeholder="Enter Title" required>
                                </div>

                                <div class="form-group">
                                    {{-- <label for="group_name">Embed Code (.MP4)</label> --}}
                                    <label for="group_name">Demo Video Code (.MP4)</label>
                                    <textarea placeholder="Enter Embed Code" name="url" rows="8" cols="50"
                                        class="form-control"></textarea>
                                    <!-- <input type="text" class="form-control" id="url" name="url" value="{{old('url')}}" placeholder="Enter Embed Code" required> -->
                                </div>

                                <div class="form-group">
                                    {{-- <label for="group_name">Embed Code (.MP4)</label> --}}
                                    <label for="group_name">Tutorial Video Code (.MP4)</label>
                                    <textarea placeholder="Enter Embed Code" name="url2" rows="8" cols="50"
                                        class="form-control"></textarea>
                                    <!-- <input type="text" class="form-control" id="url" name="url" value="{{old('url')}}" placeholder="Enter Embed Code" required> -->
                                </div>

                                <div class="form-group">
                                    <label for="group_name">Thumbnail Image</label>
                                    <input type="file" class="form-control" name="thumbnail" required
                                        accept=".png, .jpg, .jpeg">
                                </div>

                                <div class="form-group">
                                    <label for="content">Video Description</label>
                                    <textarea onkeypress="onTestChange();" placeholder="Enter Video Description"
                                        class="form-control" id="group_description" name="group_description" rows="8"
                                        cols="50" required>{{old('group_description')}}</textarea>
                                </div>

                                <div class="form-group" style="display:none">
                                    <label>Select Category</label>
                                    <select class="form-control" name="categorty" id="ssss">
                                        <option value="">please select Category</option>
                                        <option value="Demo Video" selected>Demo Video</option>
                                        <option value="Workout Video">Workout Video</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Add Demo Tag</label>
                                    <input type="text" data-role="tagsinput" max-tags="1" name="tag[]" id="" value=""
                                        class=" form-control vvv">
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </section>
</div>



<style>
    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        display: inline-block;
        padding: 4px 6px;
        color: #555;
        vertical-align: middle;
        border-radius: 4px;
        width: 100%;
        line-height: 22px;
        cursor: text;
    }

    .bootstrap-tagsinput input {
        border: none;
        box-shadow: none;
        outline: none;
        background-color: transparent;
        padding: 0 6px;
        margin: 0;
        width: auto;
        max-width: inherit;
    }

    .bootstrap-tagsinput.form-control input::-moz-placeholder {
        color: #777;
        opacity: 1;
    }

    .bootstrap-tagsinput.form-control input:-ms-input-placeholder {
        color: #777;
    }

    .bootstrap-tagsinput.form-control input::-webkit-input-placeholder {
        color: #777;
    }

    .bootstrap-tagsinput input:focus {
        border: none;
        box-shadow: none;
    }

    .bootstrap-tagsinput .badge {
        margin: 2px;
        padding: 5px 8px;
    }

    .bootstrap-tagsinput .badge [data-role="remove"] {
        margin-left: 8px;
        cursor: pointer;
    }

    .bootstrap-tagsinput .badge [data-role="remove"]:after {
        content: "×";
        padding: 0px 4px;
        background-color: rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        font-size: 13px
    }

    .bootstrap-tagsinput .badge [data-role="remove"]:hover:after {
        background-color: rgba(0, 0, 0, 0.62);
    }

    .bootstrap-tagsinput .badge [data-role="remove"]:hover:active {
        box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    }

    .tt-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        list-style: none;
        font-size: 14px;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        background-clip: padding-box;
        cursor: pointer;
    }

    .tt-suggestion {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.428571429;
        color: #333333;
        white-space: nowrap;
    }

    .tt-suggestion:hover,
    .tt-suggestion:focus {
        color: #ffffff;
        text-decoration: none;
        outline: 0;
        background-color: #428bca;
    }
</style>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
</script>

<script>
    var type;
    $('#ssss').on('change', function() {
        type =  this.value ;
    });

    (function($) {
        "use strict";
        var defaultOptions = {
            tagClass: function(item) {
                return 'badge badge-info';
            },

            focusClass: 'focus',
            itemValue: function(item) {
                return item ? item.toString() : item;
            },

            itemText: function(item) {
                return this.itemValue(item);
            },

            itemTitle: function(item) {
                return null;
            },

            freeInput: true,
            addOnBlur: true,
            maxTags: undefined,
            maxChars: undefined,
            confirmKeys: [13, 44],
            delimiter: ',',
            delimiterRegex: null,
            cancelConfirmKeysOnEmpty: false,

            onTagExists: function(item, $tag) {
                $tag.hide().fadeIn();
            },

            trimValue: false,
            allowDuplicates: false,
            triggerChange: true,
            editOnBackspace: false
        };


        /**
         * Constructor function
        */

        function TagsInput(element, options) {
            this.isInit = true;
            this.itemsArray = [];
            this.$element = $(element);
            this.$element.addClass('sr-only');
            this.isSelect = (element.tagName === 'SELECT');
            this.multiple = (this.isSelect && element.hasAttribute('multiple'));
            this.objectItems = options && options.itemValue;
            this.placeholderText = element.hasAttribute('placeholder') ? this.$element.attr('placeholder') : '';
            this.name = element.hasAttribute('name') ? this.$element.attr('name') : '';
            this.type = element.hasAttribute('type') ? this.$element.attr('type') : 'text';
            this.inputSize = Math.max(1, this.placeholderText.length);
            this.$container = $('<div class="bootstrap-tagsinput"></div>');
            this.$input = $('<input type="' + this.type + '" name="' + this.name + '" placeholder="' + this.placeholderText + '"/>').appendTo(this.$container);
            this.$element.before(this.$container);
            this.build(options);
            this.isInit = false;
        }

        TagsInput.prototype = {
            constructor: TagsInput,

            /**
             * Adds the given item as a new tag. Pass true to dontPushVal to prevent
             * updating the elements val()
            */

            add: function(item, dontPushVal, options) {
                var self = this;
                if(type == 'Workout Video'){
                if (self.itemsArray.length == 1) {
                    return;
                }
            }



                if (self.options.maxTags && self.itemsArray.length >= self.options.maxTags)

                    return;



                // Ignore falsey values, except false

                if (item !== false && !item)

                    return;



                // Trim value

                if (typeof item === "string" && self.options.trimValue) {

                    item = $.trim(item);

                }



                // Throw an error when trying to add an object while the itemValue option was not set

                if (typeof item === "object" && !self.objectItems)

                    throw ("Can't add objects when itemValue option is not set");



                // Ignore strings only containg whitespace

                if (item.toString().match(/^\s*$/))

                    return;



                // If SELECT but not multiple, remove current tag

                if (self.isSelect && !self.multiple && self.itemsArray.length > 0)

                    self.remove(self.itemsArray[0]);



                if (typeof item === "string" && this.$element[0].tagName === 'INPUT') {

                    var delimiter = (self.options.delimiterRegex) ? self.options.delimiterRegex : self.options.delimiter;

                    var items = item.split(delimiter);

                    if (items.length > 1) {

                        for (var i = 0; i < items.length; i++) {

                            this.add(items[i], true);

                        }



                        if (!dontPushVal)

                            self.pushVal(self.options.triggerChange);

                        return;

                    }

                }



                var itemValue = self.options.itemValue(item),

                    itemText = self.options.itemText(item),

                    tagClass = self.options.tagClass(item),

                    itemTitle = self.options.itemTitle(item);



                // Ignore items allready added

                var existing = $.grep(self.itemsArray, function(item) {

                    return self.options.itemValue(item) === itemValue;

                })[0];

                if (existing && !self.options.allowDuplicates) {

                    // Invoke onTagExists

                    if (self.options.onTagExists) {

                        var $existingTag = $(".badge", self.$container).filter(function() {

                            return $(this).data("item") === existing;

                        });

                        self.options.onTagExists(item, $existingTag);

                    }

                    return;

                }



                // if length greater than limit

                if (self.items().toString().length + item.length + 1 > self.options.maxInputLength)

                    return;



                // raise beforeItemAdd arg

                var beforeItemAddEvent = $.Event('beforeItemAdd', {

                    item: item,

                    cancel: false,

                    options: options

                });

                self.$element.trigger(beforeItemAddEvent);

                if (beforeItemAddEvent.cancel)

                    return;



                // register item in internal array and map

                self.itemsArray.push(item);

                //self.itemsArray.splice(self.findInputWrapper().index(), 0, item);

                // add a tag element



                var $tag = $('<span class="' + htmlEncode(tagClass) + (itemTitle !== null ? ('" title="' + itemTitle) : '') + '">' + htmlEncode(itemText) + '<span data-role="remove"></span></span>');

                $tag.data('item', item);

                self.findInputWrapper().before($tag);



                // Check to see if the tag exists in its raw or uri-encoded form

                var optionExists = (

                    $('option[value="' + encodeURIComponent(itemValue).replace(/"/g, '\\"') + '"]', self.$element).length ||

                    $('option[value="' + htmlEncode(itemValue).replace(/"/g, '\\"') + '"]', self.$element).length

                );



                // add <option /> if item represents a value not present in one of the <select />'s options

                if (self.isSelect && !optionExists) {

                    var $option = $('<option selected>' + htmlEncode(itemText) + '</option>');

                    $option.data('item', item);

                    $option.attr('value', itemValue);

                    self.$element.append($option);

                }



                if (!dontPushVal)

                    self.pushVal(self.options.triggerChange);



                // Add class when reached maxTags

                if (self.options.maxTags === self.itemsArray.length || self.items().toString().length === self.options.maxInputLength)

                    self.$container.addClass('bootstrap-tagsinput-max');



                // If using typeahead, once the tag has been added, clear the typeahead value so it does not stick around in the input.

                if ($('.typeahead, .twitter-typeahead', self.$container).length) {

                    self.$input.typeahead('val', '');

                }



                if (this.isInit) {

                    self.$element.trigger($.Event('itemAddedOnInit', {

                        item: item,

                        options: options

                    }));

                } else {

                    self.$element.trigger($.Event('itemAdded', {

                        item: item,

                        options: options

                    }));

                }

            },



            /**

             * Removes the given item. Pass true to dontPushVal to prevent updating the

             * elements val()

             */

            remove: function(item, dontPushVal, options) {

                var self = this;



                if (self.objectItems) {

                    if (typeof item === "object")

                        item = $.grep(self.itemsArray, function(other) {

                            return self.options.itemValue(other) == self.options.itemValue(item);

                        });

                    else

                        item = $.grep(self.itemsArray, function(other) {

                            return self.options.itemValue(other) == item;

                        });



                    item = item[item.length - 1];

                }



                if (item) {

                    var beforeItemRemoveEvent = $.Event('beforeItemRemove', {

                        item: item,

                        cancel: false,

                        options: options

                    });

                    self.$element.trigger(beforeItemRemoveEvent);

                    if (beforeItemRemoveEvent.cancel)

                        return;



                    $('.badge', self.$container).filter(function() {

                        return $(this).data('item') === item;

                    }).remove();

                    $('option', self.$element).filter(function() {

                        return $(this).data('item') === item;

                    }).remove();

                    if ($.inArray(item, self.itemsArray) !== -1)

                        self.itemsArray.splice($.inArray(item, self.itemsArray), 1);

                }



                if (!dontPushVal)

                    self.pushVal(self.options.triggerChange);



                // Remove class when reached maxTags

                if (self.options.maxTags > self.itemsArray.length)

                    self.$container.removeClass('bootstrap-tagsinput-max');



                self.$element.trigger($.Event('itemRemoved', {

                    item: item,

                    options: options

                }));

            },



            /**

             * Removes all items

             */

            removeAll: function() {

                var self = this;



                $('.badge', self.$container).remove();

                $('option', self.$element).remove();



                while (self.itemsArray.length > 0)

                    self.itemsArray.pop();



                self.pushVal(self.options.triggerChange);

            },



            /**

             * Refreshes the tags so they match the text/value of their corresponding

             * item.

             */

            refresh: function() {

                var self = this;

                $('.badge', self.$container).each(function() {

                    var $tag = $(this),

                        item = $tag.data('item'),

                        itemValue = self.options.itemValue(item),

                        itemText = self.options.itemText(item),

                        tagClass = self.options.tagClass(item);



                    // Update tag's class and inner text

                    $tag.attr('class', null);

                    $tag.addClass('badge ' + htmlEncode(tagClass));

                    $tag.contents().filter(function() {

                        return this.nodeType == 3;

                    })[0].nodeValue = htmlEncode(itemText);



                    if (self.isSelect) {

                        var option = $('option', self.$element).filter(function() {

                            return $(this).data('item') === item;

                        });

                        option.attr('value', itemValue);

                    }

                });

            },



            /**

             * Returns the items added as tags

             */

            items: function() {

                return this.itemsArray;

            },



            /**

             * Assembly value by retrieving the value of each item, and set it on the

             * element.

             */

            pushVal: function() {

                var self = this,

                    val = $.map(self.items(), function(item) {

                        return self.options.itemValue(item).toString();

                    });



                self.$element.val(val.join(self.options.delimiter));



                if (self.options.triggerChange)

                    self.$element.trigger('change');

            },



            /**

             * Initializes the tags input behaviour on the element

             */

            build: function(options) {

                var self = this;



                self.options = $.extend({}, defaultOptions, options);

                // When itemValue is set, freeInput should always be false

                if (self.objectItems)

                    self.options.freeInput = false;



                makeOptionItemFunction(self.options, 'itemValue');

                makeOptionItemFunction(self.options, 'itemText');

                makeOptionFunction(self.options, 'tagClass');



                // Typeahead Bootstrap version 2.3.2

                if (self.options.typeahead) {

                    var typeahead = self.options.typeahead || {};



                    makeOptionFunction(typeahead, 'source');



                    self.$input.typeahead($.extend({}, typeahead, {

                        source: function(query, process) {

                            function processItems(items) {

                                var texts = [];



                                for (var i = 0; i < items.length; i++) {

                                    var text = self.options.itemText(items[i]);

                                    map[text] = items[i];

                                    texts.push(text);

                                }

                                process(texts);

                            }



                            this.map = {};

                            var map = this.map,

                                data = typeahead.source(query);



                            if ($.isFunction(data.success)) {

                                // support for Angular callbacks

                                data.success(processItems);

                            } else if ($.isFunction(data.then)) {

                                // support for Angular promises

                                data.then(processItems);

                            } else {

                                // support for functions and jquery promises

                                $.when(data)

                                    .then(processItems);

                            }

                        },

                        updater: function(text) {

                            self.add(this.map[text]);

                            return this.map[text];

                        },

                        matcher: function(text) {

                            return (text.toLowerCase().indexOf(this.query.trim().toLowerCase()) !== -1);

                        },

                        sorter: function(texts) {

                            return texts.sort();

                        },

                        highlighter: function(text) {

                            var regex = new RegExp('(' + this.query + ')', 'gi');

                            return text.replace(regex, "<strong>$1</strong>");

                        }

                    }));

                }



                // typeahead.js

                if (self.options.typeaheadjs) {

                    // Determine if main configurations were passed or simply a dataset

                    var typeaheadjs = self.options.typeaheadjs;

                    if (!$.isArray(typeaheadjs)) {

                        typeaheadjs = [null, typeaheadjs];

                    }



                    $.fn.typeahead.apply(self.$input, typeaheadjs).on('typeahead:selected', $.proxy(function(obj, datum, name) {

                        var index = 0;

                        typeaheadjs.some(function(dataset, _index) {

                            if (dataset.name === name) {

                                index = _index;

                                return true;

                            }

                            return false;

                        });



                        // @TODO Dep: https://github.com/corejavascript/typeahead.js/issues/89

                        if (typeaheadjs[index].valueKey) {

                            self.add(datum[typeaheadjs[index].valueKey]);

                        } else {

                            self.add(datum);

                        }



                        self.$input.typeahead('val', '');

                    }, self));

                }



                self.$container.on('click', $.proxy(function(event) {

                    if (!self.$element.attr('disabled')) {

                        self.$input.removeAttr('disabled');

                    }

                    self.$input.focus();

                }, self));



                if (self.options.addOnBlur && self.options.freeInput) {

                    self.$input.on('focusout', $.proxy(function(event) {

                        // HACK: only process on focusout when no typeahead opened, to

                        //       avoid adding the typeahead text as tag

                        if ($('.typeahead, .twitter-typeahead', self.$container).length === 0) {

                            self.add(self.$input.val());

                            self.$input.val('');

                        }

                    }, self));

                }



                // Toggle the 'focus' css class on the container when it has focus

                self.$container.on({

                    focusin: function() {

                        self.$container.addClass(self.options.focusClass);

                    },

                    focusout: function() {

                        self.$container.removeClass(self.options.focusClass);

                    },

                });



                self.$container.on('keydown', 'input', $.proxy(function(event) {

                    var $input = $(event.target),

                        $inputWrapper = self.findInputWrapper();



                    if (self.$element.attr('disabled')) {

                        self.$input.attr('disabled', 'disabled');

                        return;

                    }



                    switch (event.which) {

                        // BACKSPACE

                        case 8:

                            if (doGetCaretPosition($input[0]) === 0) {

                                var prev = $inputWrapper.prev();

                                if (prev.length) {

                                    if (self.options.editOnBackspace === true) {

                                        $input.val(prev.data('item'));

                                    }

                                    self.remove(prev.data('item'));

                                }

                            }

                            break;



                            // DELETE

                        case 46:

                            if (doGetCaretPosition($input[0]) === 0) {

                                var next = $inputWrapper.next();

                                if (next.length) {

                                    self.remove(next.data('item'));

                                }

                            }

                            break;



                            // LEFT ARROW

                        case 37:

                            // Try to move the input before the previous tag

                            var $prevTag = $inputWrapper.prev();

                            if ($input.val().length === 0 && $prevTag[0]) {

                                $prevTag.before($inputWrapper);

                                $input.focus();

                            }

                            break;

                            // RIGHT ARROW

                        case 39:

                            // Try to move the input after the next tag

                            var $nextTag = $inputWrapper.next();

                            if ($input.val().length === 0 && $nextTag[0]) {

                                $nextTag.after($inputWrapper);

                                $input.focus();

                            }

                            break;

                        default:

                            // ignore

                    }



                    // Reset internal input's size

                    var textLength = $input.val().length,

                        wordSpace = Math.ceil(textLength / 5),

                        size = textLength + wordSpace + 1;

                    $input.attr('size', Math.max(this.inputSize, size));

                }, self));



                self.$container.on('keypress', 'input', $.proxy(function(event) {

                    var $input = $(event.target);



                    if (self.$element.attr('disabled')) {

                        self.$input.attr('disabled', 'disabled');

                        return;

                    }



                    var text = $input.val(),

                        maxLengthReached = self.options.maxChars && text.length >= self.options.maxChars;

                    if (self.options.freeInput && (keyCombinationInList(event, self.options.confirmKeys) || maxLengthReached)) {

                        // Only attempt to add a tag if there is data in the field

                        if (text.length !== 0) {

                            self.add(maxLengthReached ? text.substr(0, self.options.maxChars) : text);

                            $input.val('');

                        }



                        // If the field is empty, let the event triggered fire as usual

                        if (self.options.cancelConfirmKeysOnEmpty === false) {

                            event.preventDefault();

                        }

                    }



                    // Reset internal input's size

                    var textLength = $input.val().length,

                        wordSpace = Math.ceil(textLength / 5),

                        size = textLength + wordSpace + 1;

                    $input.attr('size', Math.max(this.inputSize, size));

                }, self));



                // Remove icon clicked

                self.$container.on('click', '[data-role=remove]', $.proxy(function(event) {

                    if (self.$element.attr('disabled')) {

                        return;

                    }

                    self.remove($(event.target).closest('.badge').data('item'));

                }, self));



                // Only add existing value as tags when using strings as tags

                if (self.options.itemValue === defaultOptions.itemValue) {

                    if (self.$element[0].tagName === 'INPUT') {

                        self.add(self.$element.val());

                    } else {

                        $('option', self.$element).each(function() {

                            self.add($(this).attr('value'), true);

                        });

                    }

                }

            },



            /**

             * Removes all tagsinput behaviour and unregsiter all event handlers

             */

            destroy: function() {

                var self = this;



                // Unbind events

                self.$container.off('keypress', 'input');

                self.$container.off('click', '[role=remove]');



                self.$container.remove();

                self.$element.removeData('tagsinput');

                self.$element.show();

            },



            /**

             * Sets focus on the tagsinput

             */

            focus: function() {

                this.$input.focus();

            },



            /**

             * Returns the internal input element

             */

            input: function() {

                return this.$input;

            },



            /**

             * Returns the element which is wrapped around the internal input. This

             * is normally the $container, but typeahead.js moves the $input element.

             */

            findInputWrapper: function() {

                var elt = this.$input[0],

                    container = this.$container[0];

                while (elt && elt.parentNode !== container)

                    elt = elt.parentNode;



                return $(elt);

            }

        };



        /**

         * Register JQuery plugin

         */

        $.fn.tagsinput = function(arg1, arg2, arg3) {

            var results = [];



            this.each(function() {

                var tagsinput = $(this).data('tagsinput');

                // Initialize a new tags input

                if (!tagsinput) {

                    tagsinput = new TagsInput(this, arg1);

                    $(this).data('tagsinput', tagsinput);

                    results.push(tagsinput);



                    if (this.tagName === 'SELECT') {

                        $('option', $(this)).attr('selected', 'selected');

                    }



                    // Init tags from $(this).val()

                    $(this).val($(this).val());

                } else if (!arg1 && !arg2) {

                    // tagsinput already exists

                    // no function, trying to init

                    results.push(tagsinput);

                } else if (tagsinput[arg1] !== undefined) {

                    // Invoke function on existing tags input

                    if (tagsinput[arg1].length === 3 && arg3 !== undefined) {

                        var retVal = tagsinput[arg1](arg2, null, arg3);

                    } else {

                        var retVal = tagsinput[arg1](arg2);

                    }

                    if (retVal !== undefined)

                        results.push(retVal);

                }

            });



            if (typeof arg1 == 'string') {

                // Return the results from the invoked function calls

                return results.length > 1 ? results : results[0];

            } else {

                return results;

            }

        };



        $.fn.tagsinput.Constructor = TagsInput;



        /**

         * Most options support both a string or number as well as a function as

         * option value. This function makes sure that the option with the given

         * key in the given options is wrapped in a function

         */

        function makeOptionItemFunction(options, key) {

            if (typeof options[key] !== 'function') {

                var propertyName = options[key];

                options[key] = function(item) {

                    return item[propertyName];

                };

            }

        }



        function makeOptionFunction(options, key) {

            if (typeof options[key] !== 'function') {

                var value = options[key];

                options[key] = function() {

                    return value;

                };

            }

        }

        /**

         * HtmlEncodes the given value

         */

        var htmlEncodeContainer = $('<div />');



        function htmlEncode(value) {

            if (value) {

                return htmlEncodeContainer.text(value).html();

            } else {

                return '';

            }

        }



        /**

         * Returns the position of the caret in the given input field

         * http://flightschool.acylt.com/devnotes/caret-position-woes/

         */

        function doGetCaretPosition(oField) {

            var iCaretPos = 0;

            if (document.selection) {

                oField.focus();

                var oSel = document.selection.createRange();

                oSel.moveStart('character', -oField.value.length);

                iCaretPos = oSel.text.length;

            } else if (oField.selectionStart || oField.selectionStart == '0') {

                iCaretPos = oField.selectionStart;

            }

            return (iCaretPos);

        }



        /**

         * Returns boolean indicates whether user has pressed an expected key combination.

         * @param object keyPressEvent: JavaScript event object, refer

         *     http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html

         * @param object lookupList: expected key combinations, as in:

         *     [13, {which: 188, shiftKey: true}]

         */

        function keyCombinationInList(keyPressEvent, lookupList) {

            var found = false;

            $.each(lookupList, function(index, keyCombination) {

                if (typeof(keyCombination) === 'number' && keyPressEvent.which === keyCombination) {

                    found = true;

                    return false;

                }



                if (keyPressEvent.which === keyCombination.which) {

                    var alt = !keyCombination.hasOwnProperty('altKey') || keyPressEvent.altKey === keyCombination.altKey,

                        shift = !keyCombination.hasOwnProperty('shiftKey') || keyPressEvent.shiftKey === keyCombination.shiftKey,

                        ctrl = !keyCombination.hasOwnProperty('ctrlKey') || keyPressEvent.ctrlKey === keyCombination.ctrlKey;

                    if (alt && shift && ctrl) {

                        found = true;

                        return false;

                    }

                }

            });



            return found;

        }



        /**

         * Initialize tagsinput behaviour on inputs and selects which have

         * data-role=tagsinput

         */

        $(function() {

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();

        });

    })(window.jQuery);

</script>





<script>
    function onTestChange() {



        var key = window.event.keyCode;



        // If the user has pressed enter

        if (key === 13) {

            document.getElementById("group_description").value = document.getElementById("group_description").value + "\n";

            return false;

        } else {

            return true;

        }

    }

</script>

@endsection