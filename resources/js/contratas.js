import Vue from 'vue'
import axios from "axios";

new Vue({
    el: '#app',
    data: {
        prestamo: '',
        diasPlan: '',
        comisionPrestamo: 0,
        cantidadPago: '0',
        tipoPagos: 'Pagos diarios',
        opcionesPago:1,
        daysOfWeek:[]
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
        resetEndDate:function(){
            document.getElementById("fecha_termino").value = "";
        },
        getEndTime: function(e) {

            if(this.diasPlan == "")
                return;

            if(this.tipoPagos == "Pagos diarios" && this.opcionesPago == 2 && this.daysOfWeek.length == 0)
                return;

            let strInitDate = e.target.value;
            let sendDataObject = {
                initDate:strInitDate,
                tipoPagos:this.tipoPagos,
                diasPlan: this.diasPlan,
                opcionesPago:this.opcionesPago,
                daysOfWeek:this.daysOfWeek
            }

            axios.post("/obtenerFechasPagos",sendDataObject)
                 .then(response => {
                    document.getElementById("fecha_termino").value = response.data.endTime
                 })
        }

    }


});