import Vue from 'vue'

new Vue({
    el: '#app',
    data: {
        prestamo:'',
        diasPlan:'',
        comisionPrestamo: 0,
        cantidadPago:'0',
    },
    created: function () {
        
    },
    mounted: function(){
       
    },
    computed: {
       
        porcentajeComision: function () {
          return (this.comisionPrestamo * 100 )/(this.prestamo)
        }
    },
    watch:
    {
        
        diasPlan: function (val) {
            if(this.comisionPrestamo != 0 && this.comisionPrestamo  != '')
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(this.comisionPrestamo)) / (val);
        },
        comisionPrestamo: function (val) {
            console.log(this.comisionPrestamo)
            console.log(val)
            
            if(val != 0 && val != '' && this.cantidadPago != val)
                this.cantidadPago = (parseInt(this.prestamo) + parseInt(val)) / (this.diasPlan);
        },
        /*cantidadPago: function (val) {

            if((this.comisionPrestamo != 0 && this.comisionPrestamo  != '') && (val != 0 && val != '0'))
                this.comisionPrestamo = val * this.diasPlan;
        },*/
    },
    
});