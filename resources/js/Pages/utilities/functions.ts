

const coreMakeGetRequest = async ({
  baseUrl,
  endpoint,
  isProtected = false,
  jwtToken = '',
  additionalHeaders = {},
}) => {
  let result = {
    success: false,
    message: '',
    data: null,
  };

  let error = '';

  let urlComplete = baseUrl + endpoint;
  try {
    // Définir les en-têtes de la requête en ajoutant des en-têtes supplémentaires si disponibles
    const headers = {
      ...(isProtected && { Authorization: 'Bearer ' + jwtToken }),
      ...additionalHeaders, // Fusionne les en-têtes supplémentaires
    };

    // Faire la requête GET
    let response = await axios.get(urlComplete, {
      headers: headers,
    });

    // Vérifier la réponse
    if (response.status === 200) {
      let rData = response.data;
      result.success = true;
      result.data = rData.data ?? rData;
    } else {
      error = response.data.message;
    }
  } catch (err) {
    error = err.message;
    if (err.response) {
      error = err.response.data.message;
    }
  }

  // Si une erreur est survenue, mettre à jour le message de résultat
  if (error !== '') {
    result.message = error;
  }

  return result;
};

const coreMakePostRequest = async ({
  baseUrl,
  endpoint,
  data,
  isProtected = false,
  jwtToken = '',
  additionalHeaders = {},
}) => {
  let result = {
    success: false,
    message: '',
    data: null,
  };

  let error = '';

  let urlComplete = baseUrl + endpoint;
  console.log('urlComplete', urlComplete);

  try {
    // Définir les en-têtes de la requête en ajoutant des en-têtes supplémentaires si disponibles
    const headers = {
      'Content-Type': 'application/json', // Par défaut, envoi des données en JSON
      ...(isProtected && { Authorization: 'Bearer ' + jwtToken }),
      ...additionalHeaders, // Fusionne les en-têtes supplémentaires
    };

    // Faire la requête POST avec les données et les en-têtes
    let response = await axios.post(urlComplete, data, {
      headers: headers,
    });

    // Vérifier la réponse
    if (response.status === 200 || response.status === 201) {
      let rData = response.data;
      result.success = true;
      result.data = rData.data ?? rData;
    } else {
      error = response.data.message;
    }
  } catch (err) {
    error = err.message;
    if (err.response) {
      console.log('err.response.data', err.response.data);
      result.data = err.response.data;
      error = err.response.data.message;
    }
  }

  // Si une erreur est survenue, mettre à jour le message de résultat
  if (error !== '') {
    result.message = error;
  }

  return result;
};

const coreMakePutRequest = async ({
  baseUrl,
  endpoint,
  data,
  isProtected = false,
  jwtToken = '',
  additionalHeaders = {},
}) => {
  let result = {
    success: false,
    message: '',
    data: null,
  };

  let error = '';

  let urlComplete = baseUrl + endpoint;
  try {
    // Définir les en-têtes de la requête en ajoutant des en-têtes supplémentaires si disponibles
    const headers = {
      'Content-Type': 'application/json', // Par défaut, envoi des données en JSON
      ...(isProtected && { Authorization: 'Bearer ' + jwtToken }),
      ...additionalHeaders, // Fusionne les en-têtes supplémentaires
    };

    // Faire la requête PUT avec les données et les en-têtes
    let response = await axios.put(urlComplete, data, {
      headers: headers,
    });

    // Vérifier la réponse
    if (response.status === 200 || response.status === 204) {
      let rData = response.data;
      result.success = true;
      result.data = rData.data ?? rData;
    } else {
      error = response.data.message;
    }
  } catch (err) {
    error = err.message;
    if (err.response) {
      error = err.response.data.message;
    }
  }

  // Si une erreur est survenue, mettre à jour le message de résultat
  if (error !== '') {
    result.message = error;
  }

  return result;
};

const coreMakePatchRequest = async ({
  baseUrl,
  endpoint,
  data,
  isProtected = false,
  jwtToken = '',
  additionalHeaders = {},
}) => {
  let result = {
    success: false,
    message: '',
    data: null,
  };

  let error = '';

  let urlComplete = baseUrl + endpoint;
  try {
    // Définir les en-têtes de la requête en ajoutant des en-têtes supplémentaires si disponibles
    const headers = {
      'Content-Type': 'application/json', // Par défaut, envoi des données en JSON
      ...(isProtected && { Authorization: 'Bearer ' + jwtToken }),
      ...additionalHeaders, // Fusionne les en-têtes supplémentaires
    };

    // Faire la requête PATCH avec les données et les en-têtes
    let response = await axios.patch(urlComplete, data, {
      headers: headers,
    });

    // Vérifier la réponse
    if (response.status === 200 || response.status === 204) {
      let rData = response.data;
      result.success = true;
      result.data = rData.data ?? rData;
    } else {
      error = response.data.message;
    }
  } catch (err) {
    error = err.message;
    if (err.response) {
      error = err.response.data.message;
    }
  }

  // Si une erreur est survenue, mettre à jour le message de résultat
  if (error !== '') {
    result.message = error;
  }

  return result;
};

const coreMakeDeleteRequest = async ({
  baseUrl,
  endpoint,
  isProtected = false,
  jwtToken = '',
  additionalHeaders = {},
}) => {
  let result = {
    success: false,
    message: '',
    data: null,
  };

  let error = '';

  let urlComplete = baseUrl + endpoint;
  try {
    // Définir les en-têtes de la requête en ajoutant des en-têtes supplémentaires si disponibles
    const headers = {
      ...(isProtected && { Authorization: 'Bearer ' + jwtToken }),
      ...additionalHeaders, // Fusionne les en-têtes supplémentaires
    };

    // Faire la requête DELETE avec les en-têtes
    let response = await axios.delete(urlComplete, {
      headers: headers,
    });

    // Vérifier la réponse
    if (response.status === 200 || response.status === 204) {
      result.success = true;
    } else {
      error = response.data.message;
    }
  } catch (err) {
    error = err.message;
    if (err.response) {
      error = err.response.data.message;
    }
  }

  // Si une erreur est survenue, mettre à jour le message de résultat
  if (error !== '') {
    result.message = error;
  }

  return result;
};



export {
  
  coreMakeGetRequest,
  coreMakePostRequest,
  coreMakePutRequest,
  coreMakePatchRequest,
  coreMakeDeleteRequest,
};
