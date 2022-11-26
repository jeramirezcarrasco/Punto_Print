from operator import indexOf


def ArrayChallenge(arr):
  if 2 not in arr:
    return 0
  location = indexOf(arr, 1)
  foward = 0
  while( location + foward < len(arr) and arr[location + foward ] != 2 ):
    foward +=1
  
  if(location + foward < len(arr) and arr[location + foward ] == 2):
    return foward

  backward = 0
  while( location - backward >= 0  and arr[location - backward ] != 2 ):
    print(location - backward)
    backward += 1
  return backward
  
# keep this function call here 
# print(ArrayChallenge([1, 0, 0, 0, 2, 2, 2]))
print(ArrayChallenge([2, 0, 0, 0, 2, 2, 1, 0]))