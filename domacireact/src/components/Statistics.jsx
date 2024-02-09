import React from 'react'
import { useState ,useEffect} from 'react';
import axios from 'axios';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend } from 'recharts';
import { Modal, Form } from 'react-bootstrap';
import { Button } from 'react-bootstrap';

function Statistics() {

    const[chartData, setChartData] = useState([]);
    const[showChartsModal, setChartsModal] = useState(false);
    const handleChartData = () => setChartsModal(true);

    useEffect(() => {
      const fetchLikes = async () => {
        try {
          const response = await axios.get('/api/likes', {
            headers: {
              'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
            },
            // Add params as needed, e.g., to fetch likes for a specific post or time period
          });

          console.log(response.data);
  
          const likes = response.data.data;
          // Transform the likes data
          const transformedData = transformLikesData(likes);
          setChartData(transformedData);
        } catch (error) {
          console.error('Error fetching likes:', error);
        }
      };
  
      fetchLikes();
    }, []);


    function transformLikesData(likes) {
      const likesCountByDate = likes.reduce((acc, like) => {
        // Extract the date part from the created_at timestamp
        const date = like.created_at.split('T')[0];
        if (!acc[date]) {
          acc[date] = 0;
        }
        acc[date]++;
        return acc;
      }, {});
    
      // Convert to array suitable for charting, and sort by date
      return Object.entries(likesCountByDate).map(([date, count]) => ({
        date,
        likes: count
      })).sort((a, b) => new Date(a.date) - new Date(b.date));
    }

  return (<>
    <Button type="button" variant="danger"  style={{
    display: 'block', // This makes it possible to center the button using margin
    margin: '10px auto', // Centers the button and adds margin
    borderRadius: '5px', // Gives the button slightly rounded corners
    backgroundColor: '#007bff', // Sets a different color, choose any color you like
    color: 'white', // Sets the text color to white
    padding: '10px 20px', // Adjusts the size of the button (optional)
    border: 'none', // Removes the default border (optional)
  }} onClick={handleChartData}>Show Likes Chart</Button>

<Modal show={showChartsModal} onHide={() => setChartsModal(false)}>
        <Modal.Header closeButton>
          <Modal.Title>Like Statistics</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {chartData.length > 0 ? (
            <LineChart width={500} height={300} data={chartData}
                margin={{ top: 5, right: 30, left: 20, bottom: 5 }}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="date" />
              <YAxis />
              <Tooltip />
              <Line type="monotone" dataKey="likes" stroke="#8884d8" />
            </LineChart>
          ) : (
            <p>Loading...</p>
          )}
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={() => setChartsModal(false)}>
            Close
        </Button>
        </Modal.Footer>
      </Modal>
      </>
  )
}

export default Statistics