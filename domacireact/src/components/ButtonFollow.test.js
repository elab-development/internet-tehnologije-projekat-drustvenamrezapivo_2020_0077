import React from 'react';
import { render, fireEvent,waitFor } from '@testing-library/react';
import ButtonFollow from './ButtonFollow';
import axios from 'axios';

jest.mock('axios');

describe('ButtonFollow', () => {
  it('sends a follow request on click', async () => {
    const mockSetAzurirajProfile = jest.fn();
    axios.post.mockResolvedValue({});

    const { getByText } = render(<ButtonFollow user_id="2" pozicija="profile" setAzurirajProfile={mockSetAzurirajProfile} />);
    fireEvent.click(getByText(/follow/i));

    await waitFor(() => {
    expect(axios.post).toHaveBeenCalledWith(expect.stringContaining('api/friendships'), expect.anything(), expect.anything());
    expect(mockSetAzurirajProfile).toHaveBeenCalled();})
  });
});