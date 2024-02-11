
import { render, screen, fireEvent } from '@testing-library/react';
import axios from 'axios';
import { MemoryRouter } from 'react-router-dom';
import EditProfilPage from './EditProfilPage';


jest.mock('axios');


jest.mock('react-router-dom', () => ({
  ...jest.requireActual('react-router-dom'), 
  useNavigate: () => jest.fn(), 
}));

describe('EditProfilPage', () => {
    const mockUlogovani = {
      user_id: "1",
      name: "Test User",
      email: "test@example.com",
      about: "This is a test about.",
      password: "secret",
    };
  
    it('renders correctly and allows input change', () => {
      render(
        <MemoryRouter>
          <EditProfilPage ulogovani={mockUlogovani} setUlogovani={() => {}} />
        </MemoryRouter>
      );
  
     
      const usernameInput = screen.getByPlaceholderText('first name');
      expect(usernameInput.value).toBe(mockUlogovani.name);
      fireEvent.change(usernameInput, { target: { value: 'Updated Name' } });
      expect(usernameInput.value).toBe('Updated Name');
  
      const emailInput = screen.getByPlaceholderText('email');
      expect(emailInput.value).toBe(mockUlogovani.email);
      fireEvent.change(emailInput, { target: { value: 'updated@example.com' } });
      expect(emailInput.value).toBe('updated@example.com');
  
     
    });
  
    
  });