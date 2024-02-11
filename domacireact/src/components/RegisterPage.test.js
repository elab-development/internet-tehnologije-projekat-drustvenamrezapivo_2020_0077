import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import axios from 'axios';
import { MemoryRouter } from 'react-router-dom';
import RegisterPage from './RegisterPage';


jest.mock('axios');


jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'), 
  useNavigate: () => jest.fn(), 
}));

describe('RegisterPage', () => {
    it('allows entering user information', () => {
      render(
        <MemoryRouter>
          <RegisterPage />
        </MemoryRouter>
      );
  
    
      fireEvent.input(screen.getByLabelText('Your Userame'), {
        target: { value: 'testuser' },
      });
      fireEvent.input(screen.getByLabelText('Your Email'), {
        target: { value: 'test@example.com' },
      });
      fireEvent.input(screen.getByLabelText('Password'), {
        target: { value: 'password123' },
      });
      fireEvent.input(screen.getByLabelText('Repeat your password'), {
        target: { value: 'password123' },
      });
  
    
      expect(screen.getByLabelText('Your Userame').value).toBe('testuser');
      expect(screen.getByLabelText('Your Email').value).toBe('test@example.com');
      expect(screen.getByLabelText('Password').value).toBe('password123');
      expect(screen.getByLabelText('Repeat your password').value).toBe('password123');
    });
  
    it('handles registration with matching passwords', async () => {
      axios.post.mockResolvedValue({ data: { success: true } }); 
  
      render(
        <MemoryRouter>
          <RegisterPage />
        </MemoryRouter>
      );
  
      
      fireEvent.input(screen.getByLabelText('Your Userame'), {
        target: { value: 'testuser' },
      });
      fireEvent.input(screen.getByLabelText('Your Email'), {
        target: { value: 'test@example.com' },
      });
      fireEvent.input(screen.getByLabelText('Password'), {
        target: { value: 'password123' },
      });
      fireEvent.input(screen.getByLabelText('Repeat your password'), {
        target: { value: 'password123' },
      });
      fireEvent.submit(screen.getByText('Register'));
  
      
      await waitFor(() => {
        expect(axios.post).toHaveBeenCalledWith("api/register", {
          email: "test@example.com",
          password: "password123",
          repeatedPassword: "password123",
          username: "testuser",
        });
    
      });
    });
  
 
  });