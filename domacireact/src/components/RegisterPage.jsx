import React from 'react';
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { Link } from 'react-router-dom';

function RegisterPage() {
    function handleInput(e) {
        let newUserData = userData;
        newUserData[e.target.name] = e.target.value;
        

        setUserData(newUserData);
      }

    const [userData, setUserData] = useState({
        email: "",
        password: "",
        repeatedPassword:"",
        username:"",
      });

      
    const[uspesno,setUspesno]=useState(true);
    const[postojiEmail,setPostojiEmail]=useState(false);
    const[razliciteSifre,setRazliciteSifre]=useState(false);


    let navigate = useNavigate();

    function handleRegister(e) {
        console.log(e);
        e.preventDefault();
        if(userData.password!==userData.repeatedPassword){
          
            setRazliciteSifre(true);
            setUspesno(false);
        }else{
            setRazliciteSifre(false);
       axios
          .post("api/register", userData)
          .then((response) => {  
            
    
            if (response.data.success === true) {
              
              
              navigate("/login");  
            }else{
                
                if(response.data.message==="vec postoji taj email"){
                    setPostojiEmail(true);
                }else{
                    setPostojiEmail(false);
                }
                setUspesno(false);
            }
          })
          .catch((error) => {  
            console.log(error);
            setUspesno(false);
          });
        }
      }





  return (
    <section className="vh-100" style={{backgroundColor: "#eee"}}>
  <div className="container h-100">
    <div className="row d-flex justify-content-center align-items-center h-100">
      <div className="col-lg-12 col-xl-11">
        <div className="card text-black" style={{borderRadius: "25px"}}> 
          <div className="card-body p-md-5">
            <div className="row justify-content-center">
              <div className="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p className="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                <form  onSubmit={(e)=>{handleRegister(e)}}  className="mx-1 mx-md-4">

                  <div className="d-flex flex-row align-items-center mb-4">
                    <i className="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div className="form-outline flex-fill mb-0">
                      <input type="text" id="form3Example1c" className="form-control" name="username" onInput={(e)=>handleInput(e)}/>
                      <label className="form-label" htmlFor="form3Example1c">Your Userame</label>
                    </div>
                  </div>

                  <div className="d-flex flex-row align-items-center mb-4">
                    <i className="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div className="form-outline flex-fill mb-0">
                      <input type="email" id="form3Example3c" className="form-control" name="email" onInput={(e)=>handleInput(e)}/>
                      <label className="form-label" htmlFor="form3Example3c">Your Email</label>
                      {postojiEmail===true ?  <p className="mb-0 me-2 alert alert-warning">Vec postoji ovakav mejl</p> : "" }
                    </div>
                  </div>

                  <div className="d-flex flex-row align-items-center mb-4">
                    <i className="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div className="form-outline flex-fill mb-0">
                      <input type="password" id="form3Example4c" className="form-control" name="password" onInput={(e)=>handleInput(e)}/>
                      <label className="form-label" htmlFor="form3Example4c">Password</label>
                    </div>
                  </div>

                  <div className="d-flex flex-row align-items-center mb-4">
                    <i className="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div className="form-outline flex-fill mb-0">
                      <input type="password" id="form3Example4cd" className="form-control" name="repeatedPassword" onInput={(e)=>handleInput(e)} />
                      <label className="form-label" htmlFor="form3Example4cd">Repeat your password</label>
                      {razliciteSifre===true ?  <p className="mb-0 me-2 alert alert-warning">Razlicite sifre</p> : "" }
                    </div>
                  </div>

                  

                  <div className="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" className="btn btn-primary btn-lg">Register</button>
                    <Link to="/login">
                    <button type="submit" className="btn btn-primary btn-lg">Login</button>
                    </Link>
                    {uspesno===false ?  <p className="mb-0 me-2 alert alert-warning">Neuspelo registrovanje</p> : "" }
                    
                  </div>

                </form>

              </div>
              <div className="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

               
                  <img src="https://th.bing.com/th/id/R.d2c55111f68587ffd101638d86f2c783?rik=TaSLVo7pag9A5Q&riu=http%3a%2f%2fcrownjewelgourmet.com%2fwp-content%2fuploads%2f2022%2f01%2fbeer.jpg&ehk=SsAK%2f6AijVQL68a3gsX8R3jQxdYx%2f%2bKtbN0NbtvtZwA%3d&risl=&pid=ImgRaw&r=0"
                  className="img-fluid" alt="Sample image"/> 

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  )
}

export default RegisterPage