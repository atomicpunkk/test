class pushSentence{
    /**
     * id (string): id of button for apply action 
     */
    constructor(id, url){
        this.$element = document.querySelector(id)
        this.url=url
        console.log(this.$element)
    }

    callback(){

        let $rows = document.querySelectorAll(".sentence")
        $rows.forEach($element => {
            let sentence = {
                id:$element.dataset.id,
                content: '',
                constraint: ''
            }
            sentence.constraint = $element.querySelector('.constraints').value
            sentence.content = $element.querySelector('.content').value
            this.push(sentence)
        });
    }

    push(sentence){
        let xhttp = new XMLHttpRequest();
        xhttp.open("POST", this.url, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`content=${sentence.content}&constraint=${sentence.constraint}&id=${sentence.id}`);
    }

    start(){
        this.$element.addEventListener('click',()=>this.callback())
    }
}
