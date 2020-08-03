import Vue from 'vue'
import axios from "axios";

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

            if(this.diasPlan == "")
                return;

            let strInitDate = e.target.value;
            let sendDataObject = {
                initDate:strInitDate,
                tipoPagos:this.tipoPagos,
                diasPlan: this.diasPlan
            }

            axios.post("/obtenerFechasPagos",sendDataObject)
                 .then(response => {
                    document.getElementById("fecha_termino").value = response.data.endTime
                 })
        }

    }


});