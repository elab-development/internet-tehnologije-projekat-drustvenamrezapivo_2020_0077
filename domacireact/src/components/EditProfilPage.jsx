import React from 'react'
import { useState } from "react";
import axios from "axios";
import { useNavigate } from 'react-router-dom';
import ButtonSeeProfile from './ButtonSeeProfile';
import { Form } from 'react-bootstrap';


function EditProfilPage({ulogovani,setUlogovani}) {
    let navigate=useNavigate();

    const [mojUser,setMojUser]=useState({
        "user_id":ulogovani.user_id+"",
                "username":ulogovani.name,
               "email":ulogovani.email,
               "about":ulogovani.about,
               "password":"",
               "password1":"",
                "picture":""
    });

    const[isteSifre,setIsteSifre]=useState(true);
    const[postojiEmail,setPostojiEmail]=useState(false);
    const[dobraDuzina,setDobraDuzina]=useState(true);
    const[prazniEmailIliIme,setPrazniEmailIliIme]=useState(false);


    const [editPassword, setEditPassword] = useState(false);
    
    function najpomocnija(e){
        setEditPassword(!editPassword);

        setDobraDuzina(true);
        setIsteSifre(true);
    }
    function handleEdit(e){
        if(mojUser.email=="" || mojUser.username==""){
            setPrazniEmailIliIme(true);
            setIsteSifre(true);
            setPostojiEmail(false);
            setDobraDuzina(true);
            
            return;
        }

        setPrazniEmailIliIme(false);
        setPostojiEmail(false);
        setIsteSifre(true);
        setDobraDuzina(true);
        if(editPassword){
       
            if(mojUser['password']!==mojUser['password1'] && mojUser['password']){
                
                setIsteSifre(false);
                
                
            }else{
                setIsteSifre(true);
                if(mojUser['password'].length<8){
                    setDobraDuzina(false);
                }else{
                    setDobraDuzina(true);
                    pomocna(1);
                }
            }
        }else{
            pomocna(2);
        }
    }
    function pomocna(broj){
        
        
        if(broj==2){
            
            let newUserData = mojUser;
            newUserData['password'] = ulogovani.password;
            console.log("ovde");
            console.log(ulogovani.password);
           
            setMojUser(newUserData);
        }
        
            const formData = new FormData();
           
            formData.append('username', mojUser.username);
            formData.append('email', mojUser.email);
            formData.append('about', mojUser.about);
            formData.append('password', mojUser.password);
        
            
            if(mojUser.picture==""){
              
                    axios.put('api/users/'+ulogovani.user_id, 
                    formData,{
                    headers: {
                     
                    'Content-Type': 'application/json',
                   
                      
                      'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
                     
                    },
                  }).then((response)=>{
                    
                     setUlogovani(response.data.data);
                    
                     setPostojiEmail(false);
                   

                      navigate("/profile/"+ulogovani.user_id);
                        
                  }).catch((error)=>{
                      
                      if(error.response.data.message=="vec postoji korisnik sa ovim emailom"){
                        setPostojiEmail(true);
                      }
                  })
            }else{
    
                const formData = new FormData();
                formData.append('picture', mojUser.picture);
                formData.append('email', mojUser.email);
                formData.append('username', mojUser.username);
                formData.append('about', mojUser.about);
                formData.append('password', mojUser.password);
                formData.append('user_id',mojUser.user_id);

                axios.post('api/users', formData,{
                    headers: {
                      'Content-Type': 'multipart/form-data',
            
                      
                      'Authorization': `Bearer ${window.sessionStorage.auth_token}`, 
                     
                    },
                  }).then((response)=>{
                     
                     setUlogovani(response.data.data);
                    
                      navigate("/profile/"+ulogovani.user_id);
                      
                  }).catch((error)=>{
                      
                      if(error.response.data.message=="vec postoji korisnik sa ovim emailom"){
                        setPostojiEmail(true);
                      }
                  })
                
            
        }
       
    }
    function handleInput(e){
    

        let newUserData = mojUser;
        
        if(e.target.name=='picture'){
          
            newUserData[e.target.name] = e.target.files[0];
        }else{
           
            newUserData[e.target.name] = e.target.value;
        }
      
        setMojUser(newUserData);
  
    }
    const rowStyles = {
        display: 'flex',
        // justifyContent: 'space-between',
        backgroundColor: '#f0f0f0',
        padding: '20px',
        borderRadius: '15px',
      };
      
      const colStyles = {
        borderRadius: '15px',
        marginRight: '180px',
      };
  return (
    <div className="container rounded bg-white mt-5 mb-5">
    

    <div className="row" style={rowStyles}>
    <ButtonSeeProfile user_id={window.sessionStorage.user_id} name={"Return to my profile,i dont want to change data"}/>
        <div className="col-md-3 border-right" style={colStyles}>
            <div className="d-flex flex-column align-items-center text-center p-3 py-5"><img className="rounded-circle mt-5" width="150px" height="120px" src={ulogovani? ulogovani.picture : ""}/>
           {/* <label>Change picture:<input type="file" name="picture" onChange={(e)=>{handleInput(e)}} className="font-weight-bold"/></label>  */}
           <label>Change picture:<Form.Control type="file" name="picture" onChange={(e)=>{handleInput(e)}} className="font-weight-bold"/></label> 
           </div>
        </div>
        <div className="col-md-5 border-right" style={colStyles}>
            <div className="p-3 py-5">
                <div className="d-flex justify-content-between align-items-center mb-3">
                    <h4 className="text-right">Profile Settings</h4>
                </div>
                <div className="row mt-2">
                    <div className="col-md-6"><label className="labels">Name</label><input  name="username" onChange={(e)=>{handleInput(e)}} type="text" className="form-control" placeholder="first name" defaultValue={ulogovani? ulogovani.name : ""}/></div>
                    <div className="col-md-6"><label className="labels">Email</label><input  name="email"  onChange={(e)=>{handleInput(e)}} type="email" className="form-control" defaultValue={ulogovani? ulogovani.email : ""} placeholder="email"/></div>
                    <div className="col-md-6"><label className="labels">About</label><textarea  name="about" onChange={(e)=>{handleInput(e)}} type="text" className="form-control" defaultValue={ulogovani && ulogovani.about!="null"? ulogovani.about : ""  } placeholder="about"/></div>
                </div>
                <div className="row mt-3">
                    <div className="col-md-12"><button onClick={(e)=>{najpomocnija(e)}} className="labels btn btn-danger">{!editPassword ? "I want to edit my password too" : "Better dont change password"}</button></div>
                    {editPassword? <>  <div className="col-md-12"><label className="labels">Password</label><input  name="password"  onChange={(e)=>{handleInput(e)}}  type="password" className="form-control" placeholder="password" defaultValue=""/></div>
                    <div className="col-md-12"><label className="labels">Repeat password</label><input name="password1" type="password" onChange={(e)=>{handleInput(e)}}  className="form-control" placeholder="repeated password" defaultValue=""/></div></> : <></>}
                
                </div>
                
                <div className="mt-5 text-center"><button onClick={(e)=>{handleEdit(e)}} className="btn btn-primary profile-button" type="submit">Save Profile</button></div>
                {postojiEmail? <><div ><p className='alert alert-success'>vec postoji ovakav email</p></div></> : <></>}
                {!isteSifre? <><div ><p className='alert alert-success'>sifra moraju biti iste</p></div></> : <></>}
                {prazniEmailIliIme? <><div ><p className='alert alert-success'>email i ime ne smeju biti prazni</p></div></> : <></>}
                {!dobraDuzina? <><div ><p className='alert alert-success'>sifra mora biti minimalno 8 karaktera</p></div></> : <></>}
              
            </div>
        </div>
        
        
    </div>
</div>
    
  )
}

export default EditProfilPage