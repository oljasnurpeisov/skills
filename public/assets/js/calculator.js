let Calculator = function ({calculatorId, basePrice}) {
    this.calculator = document.querySelector(calculatorId);
    this.basePrice = Number(basePrice);
    this.DOMElements = {
        duration: this.calculator.querySelector('[name = duration]'),
        quantity: this.calculator.querySelector('[name = quantity]'),
        costPerPerson: this.calculator.querySelector('[name = costPerPerson]'),
        formatRadios: this.calculator.querySelectorAll('[name = format]'),
        kkRadio: this.calculator.querySelector('.calculator-kk-radio'),
        radios: this.calculator.querySelectorAll('[type=checkbox]'),
        result: this.calculator.querySelector('.calculator__result span')
    };
};

Calculator.prototype = {
    init: function () {
        this.initListeners();
    },

    getIncreasePercentage: function () {
        let sum = 0;
        this.DOMElements.radios.forEach(function (el) {
            if (el.checked) {
                sum += Number(el.value)
            }
        });
        return sum;
    },

    calculate: function () {
        let duration = this.DOMElements.duration.value,
            increasePercentage = this.getIncreasePercentage(),
            additionalHours = increasePercentage * duration / 100,
            totalDuration = Number(duration) + additionalHours,
            preTotalPrice = totalDuration * this.basePrice,
            costPerPerson = Math.round(preTotalPrice/13),
            quantity = this.DOMElements.quantity.value,
            totalPrice = quantity * costPerPerson;
        this.DOMElements.costPerPerson.value = this.numberWithSpaces(costPerPerson);
        this.DOMElements.result.innerHTML = this.numberWithSpaces(String(totalPrice));
    },

    uncheckAllExceptOne: function (el) {
        let name = el.name;
        document.querySelectorAll(`[name=${name}]`).forEach(function (el1) {
            el1.checked = false;
        });
        el.checked = true;
    },

    initListeners: function () {
        let self = this;
        this.DOMElements.formatRadios.forEach(function (el) {
            el.addEventListener('change', function (e) {
                self.DOMElements.kkRadio.value = e.target.dataset.kk;
            });
        });

        this.DOMElements.radios.forEach(function (el) {
            el.addEventListener('change', function () {
                self.calculate();
            })
        });

        this.DOMElements.radios.forEach(function (el) {
            el.addEventListener('click', function (e) {
                if (el.checked) {
                    self.uncheckAllExceptOne(el);
                }
            })
        });

        this.DOMElements.duration.addEventListener('keyup', function () {
           self.calculate();
        });

        this.DOMElements.quantity.addEventListener('keyup', function () {
           self.calculate();
        });
    },
    
    numberWithSpaces: function (x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }
};