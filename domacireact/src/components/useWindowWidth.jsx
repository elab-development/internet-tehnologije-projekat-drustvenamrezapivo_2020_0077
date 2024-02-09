import React, { useState, useEffect } from 'react';
 

const useWindowWidth = () => {
  const [windowWidth, setWindowWidth] = useState(window.innerWidth);
  
 
  const handleResize = () => {
    setWindowWidth(window.innerWidth);
  };
 
  useEffect(() => {
    
    window.addEventListener('resize', handleResize);
 
  }, []); 
 
  return windowWidth;
 
  
  
};
 export default useWindowWidth;