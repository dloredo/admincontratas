import Vue from 'vue'

new Vue({
    el: '#app',
    data: {
        prestamo: '',
        diasPlan: '',
        comisionPrestamo: 0,
        cantidadPago: '0',
        tipoPagos: 'Pagos diarios'
    },
    created: function() {

    },
    mounted: function() {

    },
    computed: {

        porcentajeComision: function() {
            return (this.comisionPrestamo * 100) / (this.prestamo)
        }
    },
    methods: {
        diasPlanKeyUp: function() {

            if (this.comisionPrestamo != 0 && this.comisionPrestamo != '')
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (this.diasPlan);

            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } });
        },
        comisionPrestamoKeyUp: function() {
            if (this.comisionPrestamo != 0 && this.comisionPrestamo != '')
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (this.diasPlan);
        },
        pagosContrataKeyUp: function() {
            this.comisionPrestamo = (this.cantidadPago * this.diasPlan) - this.prestamo;
        },
        getEndTime: function(e) {
            var strInitDate = e.target.value;
            var splitedInitDate = strInitDate.split("-")
            var initDate = new Date(splitedInitDate[0], splitedInitDate[1], splitedInitDate[2]);
            var timeInitDate = initDate.getTime();

            if (this.tipoPagos == "Pagos diarios") {
                this.calculateEndDate(initDate);
                timeInitDate = timeInitDate + ((86400 * 1000) * this.diasPlan);
            } else
                timeInitDate = timeInitDate + (((86400 * 1000) * 7) * this.diasPlan);

            var endTime = new Date(timeInitDate);
            var endDay = (endTime.getDate() < 10) ? "0" + endTime.getDate() : endTime.getDate();
            var endMonth = (endTime.getMonth() < 10) ? "0" + endTime.getMonth() : endTime.getMonth();
            var endYear = endTime.getFullYear();
            var strEndTime = endYear + "-" + endMonth + "-" + endDay;

            document.getElementById("fecha_termino").value = strEndTime
        },
        calculateEndDate: function(initDate) {
            for (let i = 0; i < 10; i++) {
                console.log(initDate.getDay())

                initDate.setDate(initDate.getDate() + 1)
                "hola mundo"
            }
        }

    }


});