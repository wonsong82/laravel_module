// jQuery
window.$ = window.jQuery = require('jquery')
$.ajaxSetup({
    beforeSend: function(xhr, type) {
        if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    },
});

// Ant Design
import './antd.less'


// socket
import io from 'socket.io-client'
if(url('socket')!==false){
    window.socket = io(url('socket'))
}














