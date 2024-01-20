import React from 'react'
import axios from 'axios';
import { Button } from 'react-bootstrap';

function ButtonUnfollow({user_id,pozicija,azurirajPosts,setAzurirajPosts,azurirajProfile,setAzurirajProfile}) {
    function unfollow(){
       
        axios.delete('api/friendships/'+window.sessionStorage.user_id+"/"+user_id, {
          headers: {
           
            
           'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
           
          },
        }).then((response)=>{
        
        
          
          if(pozicija=="profile"){
            setAzurirajProfile(azurirajProfile=>!azurirajProfile);
          }
          if(pozicija=="posts"){
            setAzurirajPosts(azurirajPosts=>!azurirajPosts);
          }
      
    
              
        }).catch((error)=>{
            console.log(error);
        })
  
    
    
      }
  return (
  
    <Button onClick={unfollow} variant="primary">Unfollow</Button>
  )
}

export default ButtonUnfollow