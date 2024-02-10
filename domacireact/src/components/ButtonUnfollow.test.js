import React from 'react';
import { render, fireEvent,waitFor } from '@testing-library/react';
import ButtonUnfollow from './ButtonUnfollow';
import axios from 'axios';

jest.mock('axios');

describe('ButtonUnfollow', () => {
    it('sends an unfollow request on click', async () => {
      const mockSetAzurirajProfile = jest.fn();
      axios.delete.mockResolvedValue({});
  
      const { getByText } = render(<ButtonUnfollow user_id="2" pozicija="profile" setAzurirajProfile={mockSetAzurirajProfile} />);
      fireEvent.click(getByText(/unfollow/i));
  
      await waitFor(() => {
        expect(axios.delete).toHaveBeenCalledWith(expect.stringContaining('api/friendships/'), expect.anything());
        expect(mockSetAzurirajProfile).toHaveBeenCalled();
      });
    });
  });