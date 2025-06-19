export const getDataToDoc = async (numdoc: string = '') => {
  // fetch to api - by lenght numdoc 8 = dni | 11 = ruc
  const response = await fetch(`https://dniruc.apisperu.com/api/v1/ruc/${numdoc}?token=${import.meta.env.VITE_TOKEN_DNI_API}`);

  const data = await response.json();
  return data;
}