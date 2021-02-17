$(function () {
    var $document = $(document);
    var selectorRangeSlider = '[data-rangeslider]';
    var $rangeSlider = $(selectorRangeSlider);

// For ie8 support
    var textContent = ('textContent' in document) ? 'textContent' : 'innerText';

// Put value in input field
    function valueOutput(element) {
        var value = element.value;
        var output = element.parentNode.getElementsByClassName('range-slider-value')[0] || element.parentNode.parentNode.getElementsByClassName('range-slider-value')[0];
        output.value = value;
    }

// Change on slider is moving
    $document.on('input', 'input[type="range"], ' + selectorRangeSlider, function (e) {
        valueOutput(e.target);
    });

// Move slider on input's value changes
    $document.on('change', '.tarif-calc input[type="number"]', function (e) {
        var $inputRange = $(selectorRangeSlider, e.target.parentNode.parentNode.parentNode);
        var value = $('input[type="number"]', e.target.parentNode)[0].value;

        $inputRange.val(value).change();
    });

// Change slider on button click
    $document.on('click', '.tarif-calc .button-square', function (e) {
        var $inputRange = $(selectorRangeSlider, e.target.parentNode.parentNode);
        var value = $(e.target).val();

        $inputRange.val(value).change();
        return false;
    });

// Basic rangeslider initialization
    $rangeSlider.rangeslider({

        // Deactivate the feature detection slider by browser
        polyfill: false,

        // Callback function
        onInit: function () {
            valueOutput(this.$element[0]);
        },
		onSlide: function(position, value) {
			let el=this.$element[0];
			if(!el) return false;
			let f=$(el).parents('form').get(0);
			let btn=f.querySelector('.button-square[value="'+value+'"]');
			if(!f || !f['annual_consumption'] || !btn) return false
			f['annual_consumption'].value=btn.dataset.value;
			squaresChange(btn);
		},
    });
});