import React from 'react'
import { Button } from 'react-bootstrap'
import axios from 'axios';
function ButtonDeletePost({user_id,post_id,renderAll,setRenderAll}) {
    function obrisiPost(e){
   
        axios.delete('api/posts/'+user_id+"/"+post_id, {
          headers: {
         
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
         
          },
        }).then((response)=>{
       
          // setAzurirajPosts(azurirajPosts => !azurirajPosts);
          // setRenderProfile(renderProfile=>!renderProfile);
          setRenderAll(renderAll=>!renderAll);
 
       
        }).catch((error)=>{
            console.log(error);
        })        
      }
  return (
       <Button onClick={(e)=>{obrisiPost(e)}} variant="primary">Delete post</Button>
  )
}
 
export default ButtonDeletePost