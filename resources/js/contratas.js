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
        daysOfWeek:[1,2,3,4,5]
    },
    created: function() {

    },
    mounted: function() {

    },
    computed: {

        porcentajeComision: function() {
            return (((this.comisionPrestamo * 100) / (this.prestamo)) / 30).toFixed(2)
        }
    },
    watch:{
        prestamo: function(val, oldVal){
            this.calcularDatos(4);
        }
    },
    methods: {
        elegirDiasKeyUP: function(){
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } });
        },
        diasPlanKeyUp: function() {
            this.calcularDatos(1);
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } });
        },
        comisionPrestamoKeyUp: function() {
            this.calcularDatos(2);
        },
        pagosContrataKeyUp: function() {
            this.calcularDatos(3);
        },

        resetEndDate:function(){
            document.getElementById("fecha_termino").value = "";
        },
        calcularDatos: function(type){
            let dias = this.diasPlan
            
            if(this.prestamo == 0 || this.prestamo == "") return;

            if(type == 1)
            {
                if((parseInt(this.comisionPrestamo) < 0 || String(this.comisionPrestamo) == "")) {
                    return;
                }                 
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);    
                return;

            }

            if(type == 2)
            {

                if(parseInt(this.diasPlan) == 0 || String(this.diasPlan) == "" ) 
                    return;

                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);
                return;
            }

            if(type == 3)
            {

                if(parseInt(this.diasPlan) == 0 || String(this.diasPlan) == "")
                    return;

                this.comisionPrestamo = (this.cantidadPago * dias) - this.prestamo;
                return;
            }


            if(type == 4)
            {
                if((parseInt(this.diasPlan) == 0 || String(this.diasPlan) == "") && (parseInt(this.comisionPrestamo) < 0 || String(this.comisionPrestamo) == ""))
                    return;

                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);    
                return;

            }   
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