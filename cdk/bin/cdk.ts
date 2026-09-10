import { App } from "aws-cdk-lib";
import { generateAppFargateStack } from "../services/stack";
import type {
	Config,
	StageType,
} from "../../../hotelinking_gateway/fargate/config/types";
import { environments } from "../lib/config/config";
const app = new App();
const env: StageType = app.node.tryGetContext("env");
export const config: Config = environments[env];

const configurations = {
	stage: env,
	envConfig: config.env,
};

generateAppFargateStack(app, configurations);
