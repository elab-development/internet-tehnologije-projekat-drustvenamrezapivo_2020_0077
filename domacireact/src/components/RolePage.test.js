import React from 'react';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import axios from 'axios';
import RolePage from './RolePage'; 
import '@testing-library/jest-dom';


jest.mock('axios');

describe('RolePage', () => {
  const mockUsers = [
    { user_id: '1', name: 'John Doe', email: 'john@example.com', role: 'user' },
    { user_id: '2', name: 'Jane Doe', email: 'jane@example.com', role: 'admin' },
  ];

  beforeEach(() => {
    
    axios.get.mockResolvedValue({ data: { users: mockUsers } });
    
    axios.put.mockClear();
  });

  test('renders without crashing and displays most active users', async () => {
    render(<RolePage />);
    await waitFor(() => {
      expect(screen.getByText('The most active users this month')).toBeInTheDocument();
      expect(screen.getByText('John Doe')).toBeInTheDocument();
      expect(screen.getByText('Jane Doe')).toBeInTheDocument();
    });
  });

  test('displays the correct button based on user role', async () => {
    render(<RolePage />);
    await waitFor(() => {
      expect(screen.getByText('Give him/her admin')).toBeInTheDocument();
      expect(screen.getByText('Already admin')).toBeInTheDocument();
    });
  });

  test('calls setAdmin function when "Give him/her admin" button is clicked', async () => {
    window.sessionStorage.setItem('user_id', '1'); 
    axios.put.mockResolvedValue({}); 

    render(<RolePage />);
    await waitFor(() => {
      fireEvent.click(screen.getByText('Give him/her admin'));
    });

    expect(axios.put).toHaveBeenCalledWith('api/setAdmin/1', {
      headers: {
        'Authorization': `Bearer ${window.sessionStorage.auth_token}`,
      },
    });
    expect(axios.put).toHaveBeenCalledTimes(1);
  });
});