import React from 'react'
import axios from 'axios';
import { Button } from 'react-bootstrap';
 
function ButtonFollow({user_id,pozicija,azurirajPosts,setAzurirajPosts,azurirajProfile,setAzurirajProfile}) {
      function follow(){
       
        const data=new FormData();
        data.append('user1_id',window.sessionStorage.user_id);
        data.append('user2_id',user_id);
        axios.post('api/friendships',data, {
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
    <Button onClick={follow} variant="primary">Follow</Button>
  )
}
 
export default ButtonFollow