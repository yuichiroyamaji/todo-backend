# インフラ構築手順書
---
### 1. 基盤ネットワーク環境構築

※CloudFormationを使用して構築する

---
**template : basenetwork.yml**

**diagram :**
<img src="https://yuichiroyamaji-general-images.s3.ap-northeast-1.amazonaws.com/%E3%82%B9%E3%82%AF%E3%83%AA%E3%83%BC%E3%83%B3%E3%82%B7%E3%83%A7%E3%83%83%E3%83%88+2022-03-08+20.15.31.png">

**basenetwork:**

- <u>VPC</u>
    - create VPC
- <u>InternetGateway</u>
    - create InternetGateway
    - attach InternetGateway to VPC
- <u>PublicRouteTable</u>
    - create PublicRouteTable
    - apply PublicRouteTable to InternetGateway
- <u>PublicSubnet</u>
    - PublicSubnet1
        - create PublicSubnet1
        - link PublicSubnet1 to PublicRouteTable
    - PublicSubnet2
        - create PublicSubnet2
        - link PublicSubnet2 to PublicRouteTable
- <u>Elastic IP</u>
    - issue Elastic IP for NatGateway1
    - issue Elastic IP for NatGateway2
- <u>NatGateway</u>
    - NatGateway1
        - create NateGateway1 attaching to PublicSubnet1
    - NatGateway2
        - create NateGateway2 attaching to PublicSubnet2
- <u>PrivateRouteTable</u>
    - PrivateRouteTable1
        - create PrivateRouteTable1
        - apply PrivateRouteTable1 to Natgateway1
    - PrivateRouteTable2
        - create PrivateRouteTable2
        - apply PrivateRouteTable2 to Natgateway2
- <u>PrivateSubnet</u>
    - PrivateSubnet1
        - create PrivateSubnet1
        - link PrivateSubnet1 to PublicRouteTable1
    - PrivateSubnet2
        - create PrivateSubnet2
        - link PrivateSubnet2 to PublicRouteTable2


**middleware:**

- <u>ALB</u>
    - Certification Manager (Https)
    - xxx
- <u>ECR Repository</u>
- <u>IAM Role</u>
    - for CodeDeploy
    - for CodeDeploy
- <u>ECS (Fargate Cluster)</u>
    - Cluster
    - Service
        - Project
    -Task Definition
- <u>CodePipeline</u>
    - CodeCommit
    - CodeBuild
    - CodeDeploy