# Steps to RUN app:
1. Build image 
```
docker build -t anchorage .
```
2. run container
```
docker run -it anchorage sh 
```
3. exec app
```
./anchorage.php owner/repo 
./anchorage.php owner/repo --service=service_name
```