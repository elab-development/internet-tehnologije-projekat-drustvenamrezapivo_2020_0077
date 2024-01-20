import React, { useState, useEffect } from 'react';
 
// Korisnički definisana kuka za praćenje širine prozora
const useWindowWidth = () => {
  const [windowWidth, setWindowWidth] = useState(window.innerWidth);
 
  const handleResize = () => {
    setWindowWidth(window.innerWidth);
  };
 
  useEffect(() => {
    // Dodavanje event listener-a za praćenje promena širine prozora
    window.addEventListener('resize', handleResize);
 
    // // Čišćenje event listener-a prilikom unmount-a komponente
    // return () => {
    //   window.removeEventListener('resize', handleResize);
    // };
  }, []); // Prazan niz znači da će se useEffect izvršiti samo pri mount-u i unmount-u
 
  // Vraćanje trenutne širine prozora
  return windowWidth;
};
export default useWindowWidth;