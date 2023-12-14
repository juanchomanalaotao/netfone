 function getAllList(){
        return `mutation{
            listContacts{
              id
              name
              contact_no
          }
        }`
    } 

    function update(id,name,contact_no){
        return `mutation{
            updateContacts(id:${id},name:"${name}",contact_no:"${contact_no}"){
              name
              contact_no
            }
        }`
    }


    function create(name,contact_no){
        return `mutation{
            createContacts(name:"${name}", contact_no:"${contact_no}"){
              name
              contact_no
            }
          }`
    }

    function view(id){
        return `mutation{
            viewContacts(id:${id})
             {
               name
               contact_no
             }
           }`
    }

    function destroy(id){
        return `mutation{
            deleteContacts(id:${id}){
              name
              contact_no
            }
        }`;
    }

