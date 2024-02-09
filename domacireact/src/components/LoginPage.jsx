import React from 'react'
import { useState } from "react";
import axios from "axios";
import {Link} from 'react-router-dom';
import { Button } from 'react-bootstrap';

function LoginPage({addToken}) {

    const [userData, setUserData] = useState({
        email: "",
        password: "",
      });


    const[uspesno,setUspesno]=useState(true);


      function handleInput(e) {
       
        let newUserData = userData;
        
        newUserData[e.target.name] = e.target.value;
        
       
        setUserData(newUserData);
      }


      

      function handleLogin(e) {
        e.preventDefault();
        
       axios
          .post("api/login", userData)
          .then((response) => {   
            console.log(response.data);
            if (response.data.success === true) {
           
          addToken(response.data.access_token,response.data.user);  
             
            }else{
                setUspesno(false);
            }
          })
          .catch((error) => { 
            setUspesno(false);
            
          });
      }
      function seeAplication(e){
        navigate('/trial');
      }
      const buttonStyles = {
        backgroundColor: '#98FB98',
        color: '#000000',
        fontSize: '20px',
        padding: '10px 20px',
        border: 'none',
        borderRadius: '5px',
        cursor: 'pointer',
        margin:'10px',
    };


  return (
    <section className="h-100 gradient-form" style={{backgroundColor:"#eee"}}>
  <div className="container py-5 h-100">
    <div className="row d-flex justify-content-center align-items-center h-100">
      <div className="col-xl-10">
        <div className="card rounded-3 text-black">
          <div className="row g-0">
          <Button style={buttonStyles} onClick={seeAplication}>See what we are offering with trial form</Button>

            <div >
              <div className="card-body p-md-5 mx-md-4">

                <div className="text-center">
                  
                    <img src="https://foodfornet.com/wp-content/uploads/Beer-Gift-Ideas-For-Him-400x400.jpg"
                    style={{width: "185px",alt:"logo"}}/> 
                  <h4 className="mt-1 mb-5 pb-1">We are beer community</h4>
                </div>

                <form onSubmit={handleLogin}>
                  <p>Please login to your account</p>

                  <div className="form-outline mb-4">
                    <input type="email" id="form2Example11" className="form-control"
                      placeholder="email address"  name="email"   onInput={(e)=>handleInput(e)}/>
                    <label className="form-label" htmlFor="form2Example11">Email</label>
                  </div>

                  <div className="form-outline mb-4">
                    <input type="password" id="form2Example22" className="form-control"  name='password' onInput={(e)=>handleInput(e)}/>
                    <label className="form-label" htmlFor="form2Example22">Password</label>
                  </div>

                  <div className="text-center pt-1 mb-5 pb-1">
                    <button type="submit" className="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">Log
                      in</button>
                 
                    {uspesno===false ?  <p className="mb-0 me-2 alert alert-warning">Pogresni kredencijali</p> : "" }
                    
                  </div>

                  <div className="d-flex align-items-center justify-content-center pb-4">
                    <p className="mb-0 me-2">Don't have an account?</p>
                    <button type="button" className="btn btn-outline-danger">
                      <Link to="/register" className="nav-link">
                Create new
                </Link></button>
                  </div>

                </form>

              </div>
            </div>
            <div className="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div className="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 className="mb-4">We are more than just a company</h4>
                <p className="small mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                  exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  );
}

export default LoginPage