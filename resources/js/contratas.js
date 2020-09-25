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
            return (this.comisionPrestamo * 100) / (this.prestamo)
        }
    },
    watch:{
        prestamo: function(val, oldVal){
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } },1);
        }
    },
    methods: {
        elegirDiasKeyUP: function(){
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } },1);
        },
        diasPlanKeyUp: function() {

            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } },1);
        },
        comisionPrestamoKeyUp: function() {
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } },2);
        },
        pagosContrataKeyUp: function() {
            if (document.getElementById("fecha_inicio").value != '')
                this.getEndTime({ target: { value: document.getElementById("fecha_inicio").value } },3);
        },

        resetEndDate:function(){
            document.getElementById("fecha_termino").value = "";
        },
        calcularDatos: function(diasRestantes,type){
            let dias = (this.tipoPagos == "Pagos diarios" && this.opcionesPago == 2)? diasRestantes : this.diasPlan


            if(typeof(type) == "undefined" || type == 1)
            {
                if((this.cantidadPago == 0 || this.cantidadPago == "" ) && 
                    (this.comisionPrestamo == 0 || this.comisionPrestamo =="")) 
                    return;

                    if((this.cantidadPago != 0 || this.cantidadPago != "" ) && 
                    (this.comisionPrestamo != 0 || this.comisionPrestamo !=""))
                    {
                        this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);
                        this.comisionPrestamo = (this.cantidadPago * dias) - this.prestamo;
                        return;
                    } 

                if((this.cantidadPago == 0 || this.cantidadPago == "" )) 
                {
                    this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);
                    return;
                }

                if((this.comisionPrestamo == 0 || this.comisionPrestamo ==""))
                {
                    this.comisionPrestamo = (this.cantidadPago * dias) - this.prestamo;
                    return;
                }
                
            }

            if(type == 2)
            {
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (dias);
                return;
            }

            if(type == 3)
            {
                this.comisionPrestamo = (this.cantidadPago * dias) - this.prestamo;
                return;
            }

        
               
        },
        getEndTime: function(e,type) {

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
                     if(this.tipoPagos == "Pagos diarios" && this.opcionesPago == 2)
                        this.calcularDatos(response.data.diasRestantes,type)
                    else
                        this.calcularDatos(0,type)

                    document.getElementById("fecha_termino").value = response.data.endTime
                 })
        }

    }


});