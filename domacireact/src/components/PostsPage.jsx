import React from 'react';
import Post from './Post';
import axios from 'axios';
import { useState } from 'react';
import { useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { useLocation } from 'react-router-dom';
import useWindowWidth from './useWindowWidth';
import { Form } from 'react-bootstrap';
import {Button} from 'react-bootstrap';



function PostsPage({renderAll,setRenderAll}) {
  console.log("postsPage render");
  const location = useLocation();
  const params=useParams();

  const currentWindowWidth = useWindowWidth();

  const [azurirajPosts, setAzurirajPosts] = useState(false);


  const[pomocnaProm1,setPomocnaProm1]=useState(true);
  const[pomocnaProm2,setPomocnaProm2]=useState(true);
  const [maxPages,setMaxPages]=useState(2);
  const [posts,setPosts]=useState([]);
  


  
  const [filter, setFilter] = useState('');
  
   
  const [currentPage, setCurrentPage] = useState(1);

  const[najpomocnija,setNajpomocnija]=useState(1);

  
  const changePage = (page) => {//dodato
    setCurrentPage(page);
   
    setPomocnaProm2(pomocnaProm2=>!pomocnaProm2);
};
  
  const handleFilterChange = (event) => {
    setFilter(event.target.value);
  };

  useEffect(() => {
    
      setCurrentPage(1);
      setMaxPages(2);
}, []);
  
  
  useEffect(() => {
    console.log("use effect postsPage");
   
    let putanja = '';
    console.log(params);
    console.log(location);
    if (location.pathname.startsWith('/posts/')) {
      putanja = 'api/postsOfFriends/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/explore/')) {
      putanja = 'api/postsOfEnemies/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/profile/')) {
      putanja = 'api/postsOfProfile/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if(location.pathname.startsWith('/trial')){
      putanja='api/posts';
    }
 
    axios
      .get(putanja, {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      })
      .then((response) => {
        console.log(response);
        setPosts(response.data.posts);
       
      })
      .catch((error) => {
       
        console.log(error);
      });
  }, [azurirajPosts,params]);
 
 

 
  useEffect(() => {
    console.log("use effect postsPage");
   
    let putanja = '';
    console.log(params);
    console.log(location);
    if (location.pathname.startsWith('/posts/')) {
      putanja = 'api/postsOfFriends/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/explore/')) {
      putanja = 'api/postsOfEnemies/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/profile/')) {
      putanja = 'api/postsOfProfile/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if(location.pathname.startsWith('/trial')){
      putanja='api/posts';
    }
 
    axios
      .get(putanja, {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      })
      .then((response) => {
        console.log(response);
        setPosts(response.data.posts);
        setCurrentPage(1);
        setMaxPages(2);
        // setFilteredPosts(response.data.posts);
        // setCurrentPosts(response.data.posts.slice(indexOfFirstPost, indexOfLastPost));
      })
      .catch((error) => {
       
        console.log(error);
      });
  }, [pomocnaProm1]);
 
 
  useEffect(() => {
    console.log("use effect postsPage");
   
    let putanja = '';
    console.log(params);
    console.log(location);
    if (location.pathname.startsWith('/posts/')) {
      putanja = 'api/postsOfFriends/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/explore/')) {
      putanja = 'api/postsOfEnemies/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if (location.pathname.startsWith('/profile/')) {
      putanja = 'api/postsOfProfile/'+params.user_id+"?filter="+filter+"&firstN="+currentPage;
    }
    if(location.pathname.startsWith('/trial')){
      putanja='api/posts';
    }
 
    axios
      .get(putanja, {
        headers: {
          'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
        },
      })
      .then((response) => {
        console.log(response);
        setPosts(response.data.posts);
        // setCurrentPage(najpomocnija);
         if(response.data.posts.length==0){
          // setMaxPages(maxPages=>maxPages-1);
         }else{
          // setCurrentPage(currentPage=>currentPage+1);
          if(currentPage>=maxPages){
            setMaxPages(maxPages=>maxPages+1);
          }
         }
        //  setCurrentPage(page);
    // paginate(page);
   
 
 
 
        // setFilteredPosts(response.data.posts);
        // setCurrentPosts(response.data.posts.slice(indexOfFirstPost, indexOfLastPost));
      })
      .catch((error) => {
       
        console.log(error);
      });
  }, [pomocnaProm2]);



 

  

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

  const paginationStyles = {
   
    display: 'flex',
    flexWrap: 'wrap',
    gap: '5px', /* Dodajte prazan prostor između elemenata paginacije */


};
const listItemStyles= {
flex: '0 0 calc(10% - 5px)', /* Svaki element paginacije zauzima 10% širine reda i ima prazan prostor od 5px */
listStyle: 'none',
}
const buttonStyle={

width: '100%', /* Postavite dugmad paginacije da zauzimaju celu širinu roditeljskog elementa */

}

function getFilteredPosts(){
  setCurrentPage(currentPage=>1);
  setMaxPages(maxPages=>2);
  setPomocnaProm1(pomocnaProm=>!pomocnaProm);
}

  return (

    <div className="container" style={{ textAlign: 'center' }}>
        {window.sessionStorage.user_id ? <>
          <Form>
      <Form.Group  style={{ marginTop: '20px' }} controlId="filter">
        {/* <h5>Filter:</h5> */}
        <Form.Control
          type="text"
          placeholder="Filter posts with location"
          value={filter}
          onChange={handleFilterChange}
          onKeyPress={(e) => {
            if (e.key === 'Enter') {
              e.preventDefault();
              handleFilterChange(e);
            }
          }}
        />
        <Button variant="danger" onClick={(e)=>getFilteredPosts()}>Filter posts</Button>
      </Form.Group>
    </Form>
    </> : <></>}

    <p>Trenutna širina prozora: {currentWindowWidth}px</p>
   
    <h1>{location.pathname.startsWith('/explore') ? 'Posts o unfriends' : ''}</h1>
    <h1>{location.pathname.startsWith('/profile') ? 'Posts off profile' : ''}</h1>
    <h1>{location.pathname.startsWith('/posts') ? 'Posts of friends' : ''}</h1>
    <div style={postContainerStyle} >
      {posts ? (
        posts.map((post) => (
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
    <div>
    <div style={{ position: 'fixed', bottom: '20px', left: '20px', zIndex: '999' }}>
      <ul  style={paginationStyles}>
            {Array.from({ length: posts.length>=1 ? maxPages : maxPages }).map((_, index) => (
                <li style={listItemStyles} key={index}>
                    <Button style={buttonStyle} className={index + 1 === currentPage ? 'active' : ''} onClick={() => changePage(index + 1)}>
                        {index + 1}
                    </Button>
                </li>
            ))}
        </ul>
        </div>
    </div>
    
  </div>

    
  );
}

export default PostsPage;