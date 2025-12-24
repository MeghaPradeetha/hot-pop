<div class="p-b-24">
    <p class="form-label">Age Range</p>
    <div class="range-selector">
        <section class="range-slider container">
            <span class="output outputOne"></span>
            <span class="output outputTwo"></span>
            <span class="full-range"></span>
            <span class="incl-range"></span>
			<input max="60" min="20" name="min_age" step="1" type="range" value="{{ $filter->min_age ?? 20 }}">
            <input max="60" min="20" name="max_age" step="1" type="range" value="{{ $filter->max_age ?? 60 }}">
        </section>
    </div>
</div>

<script>
    var rangeOne = document.querySelector('input[name="min_age"]'),
        rangeTwo = document.querySelector('input[name="max_age"]'),
        outputOne = document.querySelector('.outputOne'),
        outputTwo = document.querySelector('.outputTwo'),
        inclRange = document.querySelector('.incl-range');

    function updateAgeView() {
        if (this.getAttribute('name') === 'min_age') {
            outputOne.innerHTML = this.value;
            outputOne.style.left = (this.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        } else {
            outputTwo.style.left = (this.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
            outputTwo.innerHTML = this.value;
        }
        if (parseInt(rangeOne.value) > parseInt(rangeTwo.value)) {
            inclRange.style.width = (rangeOne.value - rangeTwo.value) / (this.getAttribute('max') - 20) * 100 + '%';
            inclRange.style.left = (rangeTwo.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        } else {
            inclRange.style.width = (rangeTwo.value - rangeOne.value) / (this.getAttribute('max') - 20) * 100 + '%';
            inclRange.style.left = (rangeOne.value - 20) / (this.getAttribute('max') - 20) * 100 + '%';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (rangeOne) {
            updateAgeView.call(rangeOne);
            updateAgeView.call(rangeTwo);
            $('input[name="min_age"], input[name="max_age"]').on('mouseup', function() {
                this.blur();
            }).on('mousedown input', function() {
                updateAgeView.call(this);
            });
        }
    });
</script>
