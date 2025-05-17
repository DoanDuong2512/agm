import apiClient from './base';

export const getUrlLivestream = () => {
  return apiClient.get(`/meeting-config`, { 
    params: { key: 'livestream-url' } 
  });
};

  
  
  