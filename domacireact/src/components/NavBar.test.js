import { render, screen } from '@testing-library/react';
import { BrowserRouter } from 'react-router-dom';
import NavBar from './NavBar'; // Update the import path as necessary

beforeEach(() => {
    // Mock sessionStorage to provide a default user object
    const user = { role: 'admin', id: '1' }; // Adjust this object to match the expected structure
    window.sessionStorage.setItem('user', JSON.stringify(user));
  });
  
  afterEach(() => {
    // Clean up sessionStorage
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

    // Verify that all links are present
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

    // Find the logout button and click it
    const logoutButton = screen.getByText(/Logout/i);
    logoutButton.click();

    // Verify the logout function was called
    expect(mockLogout).toHaveBeenCalled();
  });
});