import React from 'react'
import { useNavigate } from 'react-router-dom'
import { useLocation } from 'react-router-dom';
import { Button } from 'react-bootstrap';
 
function ButtonSeeProfile({handleCloseDetails,user_id,name}) {
    const location = useLocation();
    let navigate=useNavigate();
    function seeProfile(){
        if(location.pathname.startsWith('/profile/')){
 
        }
        if(handleCloseDetails){
          handleCloseDetails();
        }
        navigate('/profile/'+user_id);
    }
  return (
    <Button onClick={seeProfile} variant="primary">{name}</Button>
 
  )
}
 
export default ButtonSeeProfile