import React from 'react';
import Post from './Post';
import axios from 'axios';
import { useState } from 'react';
import { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useLocation } from 'react-router-dom';



function PostsPage({renderAll,setRenderAll}) {
  console.log("postsPage render");
  const location = useLocation();
  const params=useParams();

  const [azurirajPosts, setAzurirajPosts] = useState(false);


  const[currentPosts,setCurrentPosts]=useState([]);
 
  

  
  
  useEffect(() => {
    console.log("use effect postsPage");
    
    let putanja = '';
    console.log(params);
    console.log(location);
    if (location.pathname.startsWith('/posts/')) {
      putanja = 'api/postsOfFriends/';
    }
    if (location.pathname.startsWith('/explore/')) {
      putanja = 'api/postsOfEnemies/';
    }
    if (location.pathname.startsWith('/profile/')) {
      putanja = 'api/postsOfProfile/';
    }
  
    axios
      .get(putanja + params.user_id, {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      })
      .then((response) => {
        
        setCurrentPosts(response.data.posts);
      })
      .catch((error) => {
        console.log(error);
      });
  }, [azurirajPosts,params]);
  

 

  

  const postContainerStyle = {
    display: 'flex',
    flexDirection: 'column',
    gap: '20px',
    marginTop: '20px',
    alignItems: 'center',
  };

  const postStyle = {
    textAlign: 'center',
    marginBottom: '20px',
    width: '60%',
    padding: '20px',
    border: '1px solid #ccc',
    borderRadius: '8px',
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
  };

  return (

    <div className="container" style={{ textAlign: 'center' }}>
    
   
    <h1>{location.pathname.startsWith('/explore') ? 'Posts of unfriends' : ''}</h1>
    <h1>{location.pathname.startsWith('/profile') ? 'Posts of profile' : ''}</h1>
    <h1>{location.pathname.startsWith('/posts') ? 'Posts of friends' : ''}</h1>
    <div style={postContainerStyle}>
      {currentPosts ? (
        currentPosts.map((post) => (
          <div key={`${post.user_id}_${post.post_id}`} style={postStyle}>
            <Post
              renderAll={renderAll}
              setRenderAll={setRenderAll}
              pozicija={'posts'}
              post={post}
              user_id={post.user.user_id}
              setAzurirajPosts={setAzurirajPosts}
              azurirajPosts={azurirajPosts}
            />
          </div>
        ))
      ) : (
        <></>
      )}
    </div>
    
  </div>

    
  );
}

export default PostsPage;