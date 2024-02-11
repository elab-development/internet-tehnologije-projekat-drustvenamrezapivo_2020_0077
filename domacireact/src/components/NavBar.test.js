import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import NavBar from './NavBar'; 

beforeEach(() => {
  
    const user = { role: 'admin', id: '1' };
    window.sessionStorage.setItem('user', JSON.stringify(user));
  });
  
  afterEach(() => {
  
    window.sessionStorage.clear();
  });


  
describe('NavBar', () => {
  const mockLogout = jest.fn();

  it('renders navigation links and checks for admin specific links', () => {
    render(
      <BrowserRouter>
        <NavBar logout={mockLogout} />
      </BrowserRouter>
    );

   
    expect(screen.getByText(/Beer Social Network/i)).toBeInTheDocument();
    expect(screen.getByText(/Posts/i)).toBeInTheDocument();
    expect(screen.getByText(/MyProfile/i)).toBeInTheDocument();
    expect(screen.getByText(/Explore/i)).toBeInTheDocument();
    expect(screen.getByText(/Reported content/i)).toBeInTheDocument();
    expect(screen.getByText(/Roles/i)).toBeInTheDocument();
  });

  it('renders logout button and triggers logout action on click', () => {
    render(
      <BrowserRouter>
        <NavBar logout={mockLogout} />
      </BrowserRouter>
    );

   
    const logoutButton = screen.getByText(/Logout/i);
    logoutButton.click();

  
    expect(mockLogout).toHaveBeenCalled();
  });
});