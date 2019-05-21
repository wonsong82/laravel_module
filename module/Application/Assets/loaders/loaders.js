require('./loaders.scss')


class Loader {

    constructor(){

        const _this = this
        $.fn.loader = function(command){
            this.map( (i, e) => {
                if(command == 'stop')
                    _this.stopLoading(e)

                else if(command == 'start')
                    _this.startLoading(e)

                else
                    _this.makeLoader(e)
            })
        }

        $('.loader').loader()
    }

    makeLoader(element){
        if(!element.hasAttribute('data-loader-loaded')){
            //if($(element).hasClass('lds-loader')){
                $(element).addClass('loader').addClass('lds-loader')
                this.makeLdsLoader(element)
            //}
        }
    }

    startLoading(element){
        if(!$(element).hasClass('loading')){
            const paddingLeft = $(element).data('paddingLeft')

            $(element).css({paddingLeft}).addClass('loading')
            $(element).attr('disabled', 'disabled')
        }
    }

    stopLoading(element){
        if($(element).hasClass('loading')){
            const paddingLeft = $(element).data('originalPaddingLeft')

            $(element).css({paddingLeft}).removeClass('loading')
            $(element).removeAttr('disabled')
        }
    }




    makeLdsLoader(element){
        $('<span class="loader-container"><span class="lds-rolling"><span/></span></span>').appendTo(element)

        if($(element).css('position') == 'static')
            $(element).css('position', 'relative')

        const
            conSize = $(element).innerHeight(),
            size = conSize * 0.8,
            margin = conSize * 0.1,
            originalPaddingLeft = $(element).css('padding-left')

        $(element).data('paddingLeft', conSize + 5)
        $(element).data('originalPaddingLeft', originalPaddingLeft)

        $('.loader-container', element).css({
            width: size,
            height: size,
            left: margin,
            top: margin
        })

        $(element).attr('data-loader-loaded', '')
    }




}

$(() => {
    new Loader()
})