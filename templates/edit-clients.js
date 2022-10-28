const renderTimes = (times, dia) => {
    times.innerText = ''
    const textarea = jQuery('#description')
    JSON.parse( textarea.text() )[dia].map( item => {
        const span = document.createElement('span')
            span.setAttribute('style', 'display:inline-flex; flex-wrap:wrap; margin:0 5px;')
            span.setAttribute('data-time', item)
            const itemSpan = document.createElement('span')
                itemSpan.innerText = item
            const button = document.createElement('button')
                button.innerText = '-'
                button.setAttribute('style', 'border-radius: 50px;width: 20px;height: 20px;display: flex;align-items: center;justify-content: center;')
                button.setAttribute('data-dia', dia)
                button.addEventListener('click', (ev)=>{
                    ev.preventDefault()
                    const textarea = jQuery('#description')
                    const currentDay = ev.target.dataset.dia
                    const time = ev.target.parentElement.dataset.time
                    const json = JSON.parse( textarea.text() )
                    json[currentDay] = json[currentDay].filter( t => t !== time )
                    textarea.text( JSON.stringify( json ) )
                    ev.target.parentElement.remove()
                })
        span.appendChild( itemSpan )
        span.appendChild( button )
        times.appendChild( span )
    })
}


jQuery(document).ready( ()=>{
    if( document.location.search.match(/taxonomy=configuration/i) && document.location.pathname.match(/term.php/i) ){
        const textarea = jQuery('#description')
            if( textarea.text() === ''){
                textarea.text(`{"Domingo": [],"Lunes": [],"Martes": [],"Miercoles": [],"Jueves": [],"Viernes": [],"Sabado": []}`)
            }
        const parent = textarea.parent()
        const table = document.createElement('table')
        const dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado']

        textarea.css('opacity',0)
        textarea.css('height',0)
        textarea.css('height',0)

        dias.forEach( dia => {
            const tr = document.createElement('tr')
                const label = document.createElement('td')
                    label.innerHTML = `<b>${dia}</b>`
                const times = document.createElement('td')
                    times.setAttribute('id', dia+'-times')
                    times.setAttribute('style', 'width:100%;')
                    renderTimes(times, dia)
                const action = document.createElement('td')
                    const button = document.createElement('button')
                    button.innerText = '+'
                    button.setAttribute('data-dia',dia)
                    button.addEventListener('click', (event)=>{
                        event.preventDefault()
                        let start = '', end = '', test = false
                        const currentDay = event.target.dataset.dia
                        do{
                            start = prompt('Ingresa hora de inicio')
                            if( start === null ) return
                            else test = start.match(/[0-9]{1,4}/g) && start.match(/[0-9]{1,4}/g)[0]===start
                        }while(!test)
                        do{
                            end = prompt('Ingresa hora final')
                            if( end === null ) return
                            else test = end.match(/[0-9]{1,4}/g) && end.match(/[0-9]{1,4}/g)[0]===end
                        }while(!test)
                        const textarea = jQuery('#description')
                        let json = JSON.parse( textarea.text() )
                        json[currentDay].push(`${start}-${end}`)
                        textarea.text( JSON.stringify( json ) )
                        renderTimes(jQuery(`#${currentDay}-times`)[0], dia)
                    })
                    action.appendChild(button)
                tr.appendChild(label)
                tr.appendChild(times)
                tr.appendChild(action)
            table.appendChild(tr)
        })
        parent.prepend(table)    
    }    
})

