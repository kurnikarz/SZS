const Roots = document.getElementById('Roots');

if(Roots){
    console.log(2);
    articles.addEventListener('click', (e)=>{
        if(e.target.className === 'btn btn-danger delete-root'){
            if(confirm('Are you sure?')){
                const id = e.target.getAttribute('data-id');

                fetch(`/article/delete/${id}`,{
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}