class pushSentence{
    /**
     * id (string): id of button for apply action 
     */
    constructor(id, url){
        this.$element = document.querySelector(id)
        this.url=url
        this.iswork=false
        console.log(this.$element)
    }

    async callback(){

        if(this.iswork)
        return;
    this.iswork=true
        this.$element.disabled = true



            let $loader = document.createElement("div");   
            $loader.className='loader'
            $loader.innerHTML = `<div class="lds-dual-ring"></div>`
        document.querySelector('body').appendChild($loader)
        let $rows = document.querySelectorAll(".sentence")
            let promises = [...$rows].map(async ($element) => {
                let sentence = {
                    id:$element.dataset.id,
                    content: '',
                    constraint: ''
                }
                sentence.constraint = $element.querySelector('.constraints').value
        sentence.content = $element.querySelector('.content').value
                            return this.push(sentence)    //do what you need here
                
            });
            await Promise.all(promises)
            $loader.remove()    
    

            this.iswork=false
      this.$element.disabled = false
        }

    push(sentence){
        return new Promise((resolve, reject)=>{ 
            let xhttp = new XMLHttpRequest();
            xhttp.open("POST", this.url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
           xhttp.onreadystatechange = function(){
            if (xhttp.readyState === 4){
               if (xhttp.status === 200){
                  resolve();
               } else {
                  reject(xhttp.status);
                  console.log("xhr failed");
               }
            } else {
               console.log("xhr processing going on");
            }
        }
            xhttp.send(`content=${sentence.content}&constraint=${sentence.constraint}&id=${sentence.id}`);
            })
    }

    start(){
        this.$element.addEventListener('click',()=>this.callback())
    }
}


