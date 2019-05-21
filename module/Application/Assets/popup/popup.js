require('./popup.scss')

class Popup {

    constructor(){
        const popupBtn = $('.popup-btn')

        window.currentPopup = null

        popupBtn.click( e => {
            e.preventDefault()
            this.openSinglePopup(e)
            return false
        })

        window.openSinglePopup = this.openSinglePopup.bind(this)
        window.closePopup = this.closePopup.bind(this)

    }

    openSinglePopup(e){
        const
            // button 에서 onload="openPopup(this)" 로 왔으면 후자, 아니면 전자
            eventTarget = e.currentTarget ? $(e.currentTarget) : $(e),
            url = eventTarget.attr('href')


        eventTarget.loader()
        eventTarget.loader('start')


        let modalSize = 'modal-lg';
        if(eventTarget.hasClass('popup-sm')) modalSize = 'modal-sm';
        else if(eventTarget.hasClass('popup-md')) modalSize = 'modal-md';
        else if(eventTarget.hasClass('popup-lg')) modalSize = 'modal-lg';

        let
            modal = $('<div class="popup modal fade"><div class="modal-dialog '+ modalSize +'"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Modal title</h4></div><div class="model-body no-padding"><iframe class="frame" frameborder="0" width="100%"></iframe></div></div></div></div>')

        modal.appendTo('body')

        this.modal = modal

        const
            frame = $('.frame', modal),
            dialog = $('.modal-dialog', modal),
            content = $('.modal-content', modal),
            header = $('.modal-header', modal),
            title = $('.modal-title', header)


        content.css('opacity', 0)
        content.on('mousedown', () => {
            window.currentPopup = this
        })


        frame.on('load', () => { // any page load

            if(content.css('opacity') == 0) { // if first time loading
                modal.modal('show')
                window.currentPopup = this
            }

            else { // if modal frame page changed
                this.updateSize(frame, content, header)
            }

            // after inner frame loaded
            const frameTitle = frame[0].contentDocument.title
            title.text(frameTitle)

            frame[0].contentWindow.modal = modal

            frame.contents().on('mousedown', () => {
                window.currentPopup = this
            })
        })


        // first time modal show
        modal.on('shown.bs.modal', () => {
            content.css('opacity', 1)
            eventTarget.loader('stop')
            frame[0].contentWindow.modal = modal

            const film = $('<div/>').css({width:'100%', height:'100%', position:'absolute', top:0, left:0, background:'#000', opacity:0.2})

            this.updateSize(frame, content, header)

            dialog.draggable()
            content.resizable({
                start: () => {
                    film.appendTo(content)
                },
                stop: () => {
                    film.remove()
                }
            })

            content.on('resize', () => {
                frame.css({
                    width: content.innerWidth() - 1,
                    height: content.innerHeight() - header.outerHeight() - 20
                })
            })
        })

        modal.on('hidden.bs.modal', () => {
            modal.remove()
        })


        // star the frame load
        frame.attr('src', url)
    }



    closePopup(){
        console.log("HI");
        window.currentPopup.modal.modal('hide')
    }


    updateSize(frame, content, header){
        const frameContentHeight = frame[0].contentWindow.document.body.scrollHeight

        content.height(frameContentHeight + header.outerHeight() + 25)

        frame.width(content.innerWidth() - 1)
        frame.height(content.innerHeight() - header.outerHeight() - 20)
    }

}

$(() => {
    new Popup()
})
